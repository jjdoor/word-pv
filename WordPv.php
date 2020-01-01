<?php
$arr = [];

function fileShow($dir)
{
    //遍历目录下的所有文件和文件夹
    $handle = opendir($dir);
    if ($handle == false) {
        $aa = 1;
    }
    while ($file = readdir($handle)) {
        if ($file !== '..' && $file !== '.') {
            $f = $dir . '/' . $file;
            if (is_file($f)) {
                echo '|--' . $file . '<br>';          //代表文件
                replace($f);
            } else {
                echo '--' . $file . '<br>';          //代表文件夹
                fileShow($f);
            }
        }
    }
}

function replace($file)
{
    global $arr;
    $pathinfo = pathinfo($file);
    if (isset($pathinfo['extension']) && $pathinfo['extension'] != 'vue') {
        return;
    }
    $myfile = fopen($file, "r") or die("Unable to open file!");
    $content = fread($myfile, filesize($file));
    fclose($myfile);
    $vowels = array("\\", "\r\n", "{", "}", "[", "]", ".", "-", ">", "(", ")", ",", ";", "'", '"', "/", "+", ">", "<", "=", "?", ":", "|", "-", "*", "@", "&");
    $onlyconsonants = str_replace($vowels, " ", $content);
    $keywords = preg_split("/[\s]+/", $onlyconsonants);
    foreach ($keywords as $k => $v) {
        //兼容gb2312,utf-8  //判断字符串是否全是中文
//        if (preg_match("/^[\x7f-\xff]+$/", $v)) {
        if (preg_match('/[\x{4e00}-\x{9fa5}]/u', $v)) {
            //带中文的排除
        } elseif (preg_match('/[\d]+/', $v)) {
            //全部数字的排除
        } else {
            if (isset($arr[$v])) {
                $arr[$v] = $arr[$v] + 1;
            } else {
                $arr[$v] = 1;
            }
        }

    }
    return $keywords;
}

$dir = getcwd() . "/" . '../application';
$dir = "D:\wangan\zk_wechat\src\pages";
fileShow($dir);

echo " < pre>";
arsort($arr);
print_r($arr);

//php使用spl FilesystemIterator遍历文件夹下所有文件

//$iterator = new FilesystemIterator(" ./");
//
//foreach ($iterator as $entry) {
//    //输出文件名
//    echo $entry->getFilename() . "\n";
//}