<?php

/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.8
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2016 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * The Welcome Controller.
 *
 * A basic controller example.  Has examples of how to set the
 * response body and status.
 *
 * @package  app
 * @extends  Controller
 */
class Controller_Search extends Controller {

    /**
     * The basic welcome message
     *
     * @access  public
     * @return  Response
     */
    public function action_index() {
        return Response::forge(View::forge('welcome/index'));
    }

    /**
     * download all trunkroom data
     */
    public function action_download() {
        $rawData = Model_Base_trunkroom::getCsvData();
        $header = array("サイト名", "店舗名", "住所(アクセス含む)", "電話番号", "説明文", "料金表の下の説明文", "画像URL", "タイプ", "広さ(約畳）", "広さ(幅ｘ奥行ｘ高さ)", "月額料金", "キャンペーン料金", "空き状況");
        \Common\Common::outputCSV(array(
            'title' => $header,
            'data' => $this->_formatCsvData($rawData),
            'delimiter' => ','
        ));
    }

    /**
     * format csv data
     * @param array $data
     * @return array
     */
    protected function _formatCsvData($data) {
        foreach ($data as $key => $row) {
            $row['site_name'] = $row['site_name'] . ' ';
            $row['location'] = preg_replace('/\s+/', ' ', $row['location']);
            $row['shop_comment'] = preg_replace('/\s+/', ' ',$row['shop_comment']);
            $row['money_comment'] = preg_replace('/\s+/', ' ',$row['money_comment']);
            $row['status'] = $row['status'];
            $data[$key] = $row;
        }
        return $data;
    }

}
