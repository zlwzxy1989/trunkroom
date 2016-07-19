<?php

/**
 * base model
 *
 * 
 */
class Model_Base extends \Fuel\Core\Model_Crud {

    /**
     * get data in table as array
     * @param array $conf
     * @param bool $simpleArray whether build result array as simple array
     * @return array
     */
    public static function getRawData($conf = array(), $simpleArray = false) {
        $result = array();
        return self::_ResultObjToArray(self::find($conf, self::primary_key()), $simpleArray);
    }

    protected static function _ResultObjToArray($crudObj, $simpleArray = false) {
        $result = array();
        if (!$crudObj) {
            return $result;
        }
        foreach ($crudObj as $key => $value) {
            if ($simpleArray) {
                $result[$key] = current($value->to_array());
            } else {
                $result[$key] = $value->to_array();
            }
        }
        return $result;
    }

    /**
     * do update or insert according to whether row exists
     * @param array $row
     * @param array $where
     * @return int unique id of the row on success,false on error
     */
    public static function updateOrInsert($row = array(), $where = NULL) {
        Log::info('row to be updated/inserted:' . serialize($row));
        $primaryKey = self::primary_key();
        $findRow = NULL;
        //check if row exists in db
        if ($where !== NULL) {
            $findRow = self::getRawData(array(
                        'select' => array($primaryKey),
                        'where' => $where,
                        'limit' => 1
                            ), true);
        } else {
            if (isset($row[$primaryKey])) {
                $findRow = self::getRawData(array(
                            'select' => array($primaryKey),
                            'where' => array($primaryKey => $row[$primaryKey]),
                            'limit' => 1
                                ), true);
            }
        }
        $isNew = empty($findRow);
        if (!$isNew) {
            $row[$primaryKey] = intval(current($findRow));
        }

        $model = self::forge($row);
        $model->is_new($isNew);
        $response = $model->save();
        if ($isNew) {
            return intval($response[0]);
        } else {
            return $row[$primaryKey];
        }
    }

    /**
     * start transaction
     * @return bool
     */
    public static function startTransaction() {
        return DB::start_transaction(static::get_connection(true));
    }

    /**
     * commit transaction
     * @return bool
     */
    public static function commitTransaction() {
        return DB::commit_transaction(static::get_connection(true));
    }

    /**
     * rollback transaction
     * @return bool
     */
    public static function rollbackTransaction() {
        return DB::rollback_transaction(static::get_connection(true));
    }

}
