<?php

class Controller_Cron_Trunkroom extends Controller_Cron
{

    public function action_index()
    {
        Log::info('fetch started');
        $startTime = time();
        $siteId = 2;
        $parser = new \HtmlParser\QurazHtmlParser();
        $areaList = $parser->getAreaListPageList();
        Log::info('area list: ' . serialize($areaList));
        $shopList = $parser->getShopUrlList($areaList);
        Log::info('shop list: ' . serialize($shopList));
        $fetchedShop = array();
        foreach ($shopList as $shopUrl)
        {
            Log::info('start loading:' . $shopUrl);
            if (in_array($shopUrl, $fetchedShop))
            {
                Log::info('fetched shop, continue...');
                continue;
            }
            $parser->load($shopUrl);
            //shop info
            $shopInfo = array(
                'site_id' => $siteId,
                'url' => $shopUrl,
                'name' => $parser->getShopName(),
                'location' => $parser->getShopAddress(),
                'phone' => $parser->getShopPhone(),
                'shop_comment' => $parser->getShopComment(),
                'money_comment' => $parser->getMoneyComment(),
                'img' => implode(';', $parser->getShopImgUrl())
            );
            //insert/update shop info,get shop id
            $shopId = Model_Base_Shop::updateOrInsert($shopInfo,
                    array('url' => $shopInfo['url']));
            
            Log::info('shop id: ' . $shopId);
            $roomInfoList = array();
            foreach ($parser->getRoomNodes() as $roomNode)
            {
                $roomInfoList[] = array(
                    'site_id' => $siteId,
                    'shop_id' => $shopId,
                    'room_type' => $parser->getRoomType($roomNode),
                    'space' => $parser->getRoomSpace($roomNode),
                    'volume' => $parser->getRoomVolume($roomNode),
                    'price' => $parser->getRoomPrice($roomNode),
                    'campaign_price' => $parser->getRoomCampaignPrice($roomNode),
                    'status' => $parser->getRoomStatus($roomNode)
                );
            }
            Log::info('room list: ' . serialize($roomInfoList));
            //insert trunk room info
            Model_Base_trunkroom::multiInsert($roomInfoList);
            $fetchedShop[] = $shopUrl;
        }
        $timeCost = time() - $startTime;
        Log::info('fetch finished, time used:' . $timeCost . ' s');
    }

}
