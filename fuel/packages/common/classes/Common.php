<?php

namespace Common;

class Common {

    /**
     * 从array中获取key对应的value,如key对应的值不存在则返回default_value
     *
     * @param array $arr
     * @param string $key
     * @param mixed $default_value
     *
     * @return mixed
     * */
    public static function getValueFromArr($arr = array(), $key = '', $default_value = '') {
        return isset($arr[$key]) ? $arr[$key] : $default_value;
    }

    /**
     * 根据$default_arr的key获取arr中相同key的value,如arr中不存在相同的key则使用$default_arr中的value,返回修改后的$default_arr
     *
     * @param array $arr
     * @param array $default_arr
     * @param bool $is_set_undefined 如设置为true,则default里未设置的字段在arr里有,则接受
     *
     * @return array
     * */
    public static function getValuesFromArr($arr = array(), $default_arr = array(), $is_set_undefined = false) {
        foreach ($default_arr as $key => $value) {
            $default_arr[$key] = self::getValueFromArr($arr, $key, $default_arr[$key]);
        }
        if ($is_set_undefined) {
            foreach ($arr as $key => $value) {
                if (!isset($default_arr[$key])) {
                    $default_arr[$key] = $value;
                }
            }
        }

        return $default_arr;
    }

    /**
     * 输出json格式数据,主要用于接口结果输出
     *
     * @param array $data
     */
    public static function outputJson($data = array()) {
        header('Content-type: application/json; charset=gbk');

        echo json_encode(self::gbk2utf8($data));
    }

    /**
     * 输出json格式数据并退出,主要用于接口结果输出
     *
     * @param array $data
     */
    public static function outputJsonAndExit($data = array()) {
        self::outputJson($data);
        exit;
    }

    /**
     * 输出内容,带上时间和换行符
     *
     * @param string $str
     */
    public static function outputLog($str = '') {
        echo date('Y-m-d H:i:s', time()) . ' ' . $str . PHP_EOL;
    }

    /**
     * 输出csv文本
     * @param array $param 各参数见模板注释
     * @return boolean 是否成功输出
     */
    public static function outputCSV($param = array()) {
        $defaultParam = array(
            //文件名
            'filename' => 'downlowd.csv',
            //表头前的数据,用于自定义输出内容
            //二元数组,每个元素为一行数据,每行数据也是数组,每个元素是一列的值
            'beforeTitle' => array(),
            //表头数组,每个元素为一列元素的表头
            'title' => array(),
            //数据数组,格式同beforeTitle
            'data' => array(),
            //文件末尾的数据数组,格式同beforeTitle
            'beforeEOF' => array(),
            //字段间分隔符
            'delimiter' => ',',
            //隔多少行刷新输出buffer
            'bufferLimit' => 1000
        );
        $param = Common::getValuesFromArr($param, $defaultParam);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename='{$param['filename']}'");
        header('Cache-Control: max-age=0');
        $fp = fopen('php://output', 'a');
        $outputOrder = array('beforeTitle', 'title', 'data', 'beforeEOF');
        $lineCounter = 0;
        foreach ($outputOrder as $outputKey) {
            if (!is_array($param[$outputKey])) {
                fclose($fp);
                return false;
            }
            if ($outputKey === 'title') {
                //标题只有一行
                fputcsv($fp, $param[$outputKey], $param['delimiter']);
                $lineCounter++;
            } else {
                foreach ($param[$outputKey] as $rowData) {
                    fputcsv($fp, $rowData, $param['delimiter']);
                    $lineCounter++;
                    if ($lineCounter === $param['bufferLimit']) {
                        ob_flush();
                        flush();
                        $lineCounter = 0;
                    }
                }
            }
        }
        fclose($fp);
        return true;
    }

    public static function array_column($array, $column) {
        if (function_exists('array_columun')) {
            return array_column($array, $column);
        } else {
            $newArray = array();
            foreach ($array as $val) {
                if (isset($val[$column])) {
                    $newArray[] = $val[$column];
                }
            }
            return $newArray;
        }
    }

    public static function br2nl($text) {
        return preg_replace('/<br\\s*?\/??>/i', '', $text);
    }

}
