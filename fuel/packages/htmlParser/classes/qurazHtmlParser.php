<?php

namespace HtmlParser;

/**
 * htmlParser for quraz
 *
 * @author zhengxy <zlwzxy1989@gmail.com>
 */
class QurazHtmlParser extends HtmlParserBase {

    /**
     * @var string site host
     */
    protected $_host = 'http://www.quraz.com';

    /**
     * get area`s url list from start page
     * @return string
     */
    public function getAreaListPageList() {
        $this->load($this->_host . '/shop/');
        $listSelector = array();
        for ($i = 3; $i < 10; $i++) {
            $listSelector[] = '.shop_bg .shop_bg_tx0' . $i . ' a';
        }
        $listSelector = implode(',', $listSelector);
        $listA = $this->html->find($listSelector);
        $result = array();
        foreach ($listA as $a) {
            $url = $a->href;
            if (!self::isAbsoluteUrl($url)) {
                $url = $this->_host . $url;
            }
            $result[] = $url;
        }
        return $result;
    }

    /**
     * get shop page url from area url list
     * @param type $list
     * @return string
     */
    public function getShopUrlList($list = array()) {
        $result = array();
        foreach ($list as $url) {
            $this->load($url);
            $listA = $this->html->find('.area_leftbox a,.con_main_tx_new table a,.sapporo_area_name a');
            if (!$listA) {
                //if detail page`s url is not found,move to detail page list
                $result[] = $url;
            } else {
                foreach ($listA as $a) {
                    $detailPageUrl = $a->href;
                    if (!self::isAbsoluteUrl($detailPageUrl)) {
                        $detailPageUrl = $this->_host . $detailPageUrl;
                    }
                    $result[] = $detailPageUrl;
                }
            }
        }
        return $result;
    }

    /**
     * get shop name
     * @return string
     */
    public function getShopName() {
        $result = '';
        $node = $this->html->find('#breadcrumbs_area li', -1);
        if ($node) {
            $result = trim(str_replace('&nbsp;＞&nbsp;', '', $node->innertext));
        }
        return $result;
    }

    /**
     * get shop address
     * @return string
     */
    public function getShopAddress() {
        $result = '';

        $node = $this->html->find('.shop_map2', 0);
        if ($node) {
            $result = trim(str_replace(array('アクセス', '&nbsp;'), '', \Common\Common::br2nl($node->plaintext)));
        }
        return $result;
    }

    /**
     * get shop phone
     * @return string
     */
    public function getShopPhone() {
        $result = '';
        $node = $this->html->find('.tx_120b', 0);
        if ($node) {
            $result = trim($node->plaintext);
        }
        return $result;
    }

    /**
     * get shop comment
     * @return string
     */
    public function getShopComment() {
        $result = '';
        $node = $this->html->find('div[style^=float:left; font-size:110%; line-height:2; margin-left:5px; margin-top:5px; margin-bottom:20px;]', 0);
        if ($node) {
            $result = trim(str_replace(array('お店の中を見る', '&nbsp;'), '', \Common\Common::br2nl($node->plaintext)));
        }
        return $result;
    }

    /**
     * get money comment
     * @return string
     */
    public function getMoneyComment() {
        $result = '';
        $node = $this->html->find('.con_main_tx_new', 0);
        if ($node) {
            foreach ($node->find('img,div,span') as $tag) {
                $tag->innertext = '';
            }
            $result = trim(str_replace(array('よくある質問', '&nbsp;'), '', strip_tags(\Common\Common::br2nl($node->innertext, ''))));
        }
        return $result;
    }

    /**
     * get shop img url
     * @return array
     */
    public function getShopImgUrl() {
        $result = array();
        $nodes = $this->html->find('ul.amazingslider-slides img');
        if ($nodes) {
            foreach ($nodes as $img) {
                $url = $img->src;
                if (!self::isAbsoluteUrl($url)) {
                    $url = $this->_host . $url;
                }
                $result[] = $url;
            }
        }
        return $result;
    }

    /**
     * get trunk room nodes
     * @return array
     */
    public function getRoomNodes() {
        $result = array();
        $tableList = $this->html->find('#ctl00_ContentPlaceHolderMain_ShopUnits_panelStyle2015 table.pricelist2_table');
        if ($tableList) {
            foreach ($tableList as $table) {
                $trList = $table->find('tr');
                if ($trList) {
                    for ($i = 0; $i < count($trList); $i+=3) {
                        $result[] = $trList[$i];
                    }
                }
            }
        }
        return $result;
    }

    /**
     * no room type field in this site,use room space(string) instead
     * @param simple_html_dom_node $node
     * @return string
     */
    public function getRoomType($node) {
        $result = '';
        $subNode = $node->find('.pricelist2_size2', 0);
        if ($subNode) {
            $result = trim($subNode->plaintext);
        }
        return $result;
    }

    public function getRoomSpace($node) {
        return floatval($this->getRoomType($node));
    }

    /**
     * no volume field in this site, reuturn empty
     * @param simple_html_dom_node $node
     * @return string
     */
    public function getRoomVolume($node) {
        return '';
    }

    /**
     * get room price
     * @param simple_html_dom_node $node
     * @return int
     */
    public function getRoomPrice($node) {
        $result = 0;
        $subNode = $node->find('.pricelist2_price2', 0);
        if ($subNode) {
            $result = self::moneyStrToInt($subNode->plaintext);
        }
        return $result;
    }

    /**
     * get campaign price
     * @param simple_html_dom_node $node
     * @return int
     */
    public function getRoomCampaignPrice($node) {
        $result = 0;
        $subNode = $node->find('.pricelist2_cam2', 0);
        if ($subNode) {
            $result = self::moneyStrToInt($subNode->plaintext);
        }
        return $result;
    }

    /**
     * get room status
     * @param simple_html_dom_node $node
     * @return string
     */
    public function getRoomStatus($node) {
        $result = '';
        $subNode = $node->find('.pricelist2_vacant2_bg', 0);
        if ($subNode) {
            $result = trim($subNode->plaintext);
        }
        return $result;
    }

}
