<?php

namespace HtmlParser;

/**
 * base class of htmlParser
 *
 * @author zhengxy <zhengxy@2345.com>
 */
abstract class HtmlParserBase
{

    public $html;
    protected $_host;

    public function __construct($filename = NULL)
    {
        $this->html = new \simple_html_dom();
        if ($filename !== NULL)
        {
            $this->html->load_file($filename);
        }
    }

    public function load($filename)
    {
        $this->html->load_file($filename);
    }

    public abstract function getAreaListPageList();

    public abstract function getShopUrlList();

    public abstract function getShopName();

    public abstract function getShopAddress();

    public abstract function getShopPhone();

    public abstract function getShopComment();

    public abstract function getMoneyComment();

    public abstract function getShopImgUrl();

    public abstract function getRoomNodes();

    public abstract function getRoomType($node);

    public abstract function getRoomSpace($node);

    public abstract function getRoomVolume($node);

    public abstract function getRoomPrice($node);

    public abstract function getRoomCampaignPrice($node);

    public abstract function getRoomStatus($node);

    public static function isAbsoluteUrl($url)
    {
        return preg_match('/^http[s]?:\/\//', $url);
    }

    /**
     * convert money string to int
     * @param string $str
     * @return int
     */
    public static function moneyStrToInt($str)
    {
        return intval(str_replace(array(',', '円'), '', trim($str)));
    }

}
