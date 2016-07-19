<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of site
 *
 * @author Administrator
 */
class Model_Base_trunkroom extends \Model_Base {

    protected static $_table_name = 'trunk_room';
    protected static $_updated_at = 'updated_at';
    protected static $_created_at = 'created_at';
    protected static $_mysql_timestamp = false;

    /**
     * insert multiple rows using transaction
     * @param array $data
     * @return boolean
     */
    public static function multiInsert($data) {
        if (!is_array($data)) {
            return false;
        }
        try {
            $model = self::forge();
            $model->startTransaction();
            //delete trunkroom data in this shop first if exists
            $trunkRoomIdList = self::getRawData(array(
                        'select' => array('id'),
                        'where' => array('shop_id' => $data[0]['shop_id']
                        )
                            ), true);
            Log::info('trunkroom to be deleted: ' . implode(',', $trunkRoomIdList));
            if (!empty($trunkRoomIdList)) {
                DB::query("DELETE FROM " . self::$_table_name . " WHERE `id` IN (" . implode(',', $trunkRoomIdList) . ")")->execute();
            }
            foreach ($data as $row) {
                //delete primary key
                $row[self::primary_key()] = NULL;
                log::info('trunkroom to be added:' . serialize($row));
                $model->set($row);
                $model->is_new(true);
                $model->save();
            }
            $model->commitTransaction();
        } catch (Exception $e) {
            Log::error('transaction error:' . serialize($e));
            $model->rollbackTransaction();
            return false;
        }
    }

    public static function getCsvData() {
        $result = DB::select(array('site.name', 'site_name'), array('shop.name', 'shop_name'), 'shop.location', 'shop.phone', 'shop.shop_comment', 'shop.money_comment', 'shop.img', 't_r.room_type', 't_r.space', 't_r.volume', 't_r.price', 't_r.campaign_price', 't_r.status')->from(array(self::$_table_name, 't_r'))->join('shop', 'LEFT')->ON('t_r.shop_id', '=', 'shop.id')->join('site', 'LEFT')->ON('shop.site_id', '=', 'site.id')->execute();
        if ($result) {
            return $result->as_array();
        }
        return array();
    }

}
