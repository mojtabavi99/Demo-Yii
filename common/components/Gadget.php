<?php

namespace common\components;

use common\models\MetaTag;
use common\models\User;
use common\modules\main\models\VerifyCode;
use common\modules\main\models\Category;
use Yii;
use common\components\SendSms;

class Gadget
{
    // برای رفع باگ و نمایش داده ها برای بررسی استفاده میشود
    public static function previewItem($input, $exit = true)
    {
        echo '<pre>';
        echo '====================== INPUT ======================' . '<br>';
        echo '===================================================' . '<br><br>';
        print_r($input);
        echo '<br><br>';
        if ($exit)
            exit;
    }
    public static function previewItems($exit = true, ...$inputs)
    {
        echo '<pre>';
        foreach ($inputs as $input) {
            echo '====================== INPUT ======================' . '<br>';
            echo '===================================================' . '<br><br>';
            print_r($input);
            echo '<br><br>';
        }
        if ($exit)
            exit;
    }

    public static function setPost()
    {
        $post = null;
        if (isset($_POST) && $_POST != null) {
            $post = $_POST;
        }
        if ($post == null) {
            $post = json_decode(file_get_contents('php://input'), true);
        }
        return $post;
    }
    // ارسال اس ام اس تاییدی
    public static function sendVerifyCode($mobile)
    {
        $model = new VerifyCode();

        $model->mobile = $mobile;
//        $model->code = substr(str_shuffle("0123456789"), 0, 4);
        $model->code = '1234';
        $model->credit_date = Jdf::jmktime();
        if ($model->save(false)) {
//             SendSms::VSMS($mobile, 'Verify', $model->code, null, null);
        }
    }
    // آپلود فایل
    public static function yiiUploadFile($file, $folder = null, $name = null): array
    {
        $send = ['error' => true];
        if ($name) {
            $fileName = $name;
        } else {
            $fileName = $file->baseName .'_'. Jdf::jmktime();
        }

        if ($folder) {
            if ($folder == 'favicon') {
                $upload = $file->saveAs(\Yii::getAlias('@frontendWeb') . '/' . $fileName . '.' . $file->extension);;
            } else {
                $upload = $file->saveAs(\Yii::getAlias('@upload') . '/' . $folder . '/' . $fileName . '.' . $file->extension);
            }
        } else {
            $upload = $file->saveAs(\Yii::getAlias('@upload') . '/' . $fileName . '.' . $file->extension);
        }
        if ($upload) {
            $send = ['error' => false, 'path' => $fileName . '.' . $file->extension];
        }
        return $send;
    }
    // آپلود گروهی فایل
    public static function yiiUploadFiles($files, $folder = null, $name = null): array
    {
        $path = array();
        if ($files) {
            foreach ($files as $file) {
                if ($name) {
                    $fileName = $name .'___'. Jdf::jmktime() . rand();
                } else {
                    $fileName = $file->baseName .'_'. Jdf::jmktime() . rand();
                }
                if ($folder) {
                    $targetPath = \Yii::getAlias('@upload') . '/' . $folder . '/' . $fileName . '.' . $file->extension;
                } else {
                    $targetPath = \Yii::getAlias('@upload') . '/' . $fileName . '.' . $file->extension;
                }
                if ($file->saveAs($targetPath)) {
                    array_push($path, $fileName . '.' . $file->extension);
                }
            }
        }
        return $path;
    }
    // آپلود فایل
    public static function phpUploadFile($file, $extensions , $folder = 'tmp'): array
    {
        // بررسی صحیح بودن فایل
        $image_validate = getimagesize($file['tmp_name']);
        if($image_validate !== false) {
            $filename = Jdf::jmktime() . '_' . basename($file['name']);
            // آدرس کامل به همراه نام فایل
            if ($folder != null){
                $path = Yii::getAlias('@upload') . '/' . $folder . '/' . $filename;
            }else{
                $path = Yii::getAlias('@upload') . '/' . $filename;
            }
            // پسوند فایل دریافتی
            $extension = strtolower(pathinfo($path,PATHINFO_EXTENSION));
            // بررسی پسوند های قابل قبول
            if (!in_array($extension, $extensions)) {
                return [
                    'error' => true,
                    'message' => 'پسوند فایل مورد قبول نیست',
                ];
            }
            // انتقال فایل به پوشه مد نظر
            if (move_uploaded_file($file['tmp_name'], $path)) {
                return [
                    'error' => false,
                    'path' => $filename,
                ];
            } else {
                return [
                    'error' => true,
                    'message' => 'خطا در بارگذاری فایل',
                ];
            }
        } else {
            return [
                'error' => true,
                'message' => 'اشکال در بررسی فایل ارسالی',
            ];
        }
    }
    //
    public static function fileExist($name, $folder = null)
    {
        if ($folder == null) {
            $file = \Yii::getAlias('@upload') . '/' . $name;
        } else {
            $file = \Yii::getAlias('@upload') . '/' . $folder . '/' . $name;
        }
        if (file_exists($file)) {
            return true;
        }
        return false;
    }
    // نمایش عکس
    public static function showFile($name, $folder = null): string
    {
        if (self::fileExist($name, $folder)) {
            if ($folder == null) {
                return '/upload/' . $name;
            } else {
                return '/upload/' . $folder . '/' . $name;
            }
        }else {
            if ($folder == null) {
                return '/upload/default.png';
            } else {
                return '/upload/' . $folder . '/default.png';
            }
        }
    }
    // حذف فایل
    public static function deleteFile($name, $folder)
    {
        if ($folder == null) {
            $file = \Yii::getAlias('@upload') . '/' . $name;
        } else {
            $file = \Yii::getAlias('@upload') . '/' . $folder . '/' . $name;
        }
        if (file_exists($file)) {
            unlink($file);
        }
    }
    // تغییر اسم فایل
    public static function renameFile($file, $currentFolder, $newFolder, $filename): array
    {
        $oldFile = Yii::getAlias('@upload') . '/' . $currentFolder . '/' . $file;
        if (file_exists($oldFile)) {
            $fileInfo = pathinfo($oldFile);
            $filename = $filename . '.' . $fileInfo['extension'];
            $newFile = Yii::getAlias('@upload') . '/' . $newFolder . '/' . $filename;
            if (!file_exists($newFile)) {
                if (rename($oldFile, $newFile)) {
                    return [
                        'error' => false,
                        'name' => $filename,
                    ];
                }
            }
        }
        return [
            'error' => true,
            'name' => null,
        ];
    }
    //دریافت نقش کاربر
    public static function getRoleByUserId($id): string
    {
        $role_info = \Yii::$app->authManager->getRolesByUser($id);
        if ($role_info) {
            $role = (is_array($role_info)) ? array_shift($role_info)->name : '';
        } else {
            $role = '';
        }
        return $role;
    }
    // بررسی صحیح بودن ساختار شماره تلفن وارد شده
    public static function validateMobileFormat($mobile): array
    {
        $mobile = self::convertToEnglish($mobile);
        if (is_numeric($mobile)){
            $numbers = str_split($mobile);
            if (count($numbers) == 11 && $numbers[0] == 0 && $numbers[1] == 9) {
                return ['error' => false, 'mobile' => $mobile];
            }
        }
        return ['error' => true];
    }
    // اطمینان از عدد بودن ورودی
    public static function validateNumbers($num): array
    {
        $num = (int)(self::convertToEnglish($num));
        if ($num == 0){
            return ['error' => true];
        }else{
            return ['error' => false , 'value' => $num];
        }
    }
    // convert seconds to clock format
    public static function secToClock($sec): string
    {
        $h = (int)($sec/3600);
        $sec = $sec - ($h*3600);
        $m = (int)($sec/60);
        $s = (int)($sec - ($m*60));
        if ($h<10){
            $h = '0'.$h;
        }
        if ($m<10){
            $m = '0'.$m;
        }
        if ($s<10){
            $s = '0'.$s;
        }
        return $h . ':' . $m . ':' . $s;
    }
    //تبدیل تاریخ شمسی یه time stamp
    public static function JalaliDateToTimeStamp($date)
    {
        $explodeDate = explode('/', $date);

        if (isset($explodeDate[0]) && isset($explodeDate[1]) && isset($explodeDate[2])) {
            $date = Jdf::jalali_to_gregorian($explodeDate[0], $explodeDate[1], $explodeDate[2]);
            if ($date == 0) {
                return $date;
            }
            return self::GregorianDateToTimeStamp($date[0] . '/' . $date[1] . '/' . $date[2]);
        }
        return $date;
    }
    //تبدیل تاریخ میلادی یه time stamp
    public static function GregorianDateToTimeStamp($date, $h = 0, $m = 0, $s = 0)
    {
        $h = 0;
        $m = 0;
        $s = 0;
        if (strpos($date, ' ') !== false) {
            $te = explode(' ', $date);
            $date = $te[0];
            $timeArray = explode(':', (string)$te[1]);
            $h = $timeArray[0];
            $m = $timeArray[1];
            $s = $timeArray[2];
        }
        if (strpos($date, '/') === false) {
            return 0;
        }
        $date = explode('/', (string)$date);
        if (count($date) != 3 || checkdate($date[1], $date[2], $date[0]) == false) {
            return 0;
        }
        $h = 0;
        $m = 0;
        $s = 0;
        return mktime($h, $m, $s, $date[1], $date[2], $date[0]);
    }
    // تغییر اعداد از انگلیسی به فارسی
    public static function convertToPersian($str, $mf = '٫')
    {
        $num_a = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '.');
        $key_a = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹', $mf);
        return str_replace($num_a, $key_a, $str);
    }
    // تغییر اعداد از فارسی به انگلیسی
    public static function convertToEnglish($str)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $str =  str_replace($persianDecimal, $newNumbers, $str);
        $str =  str_replace($arabicDecimal, $newNumbers, $str);
        $str =  str_replace($arabic, $newNumbers, $str);
        return str_replace($persian, $newNumbers, $str);
    }
    //
    public static function calculateDiscount($price, $discount): int
    {
        return (int)$price - (int)((int)$price * (int)$discount / 100);
    }
    //
    public static function getClientOs($system): array
    {
        $system = strtolower($system);
        if (str_contains($system, 'iphone')) {
            return [
                'belong' => 'mobile',
                'os' => 'iphone',
            ];
        }elseif (str_contains($system, 'android')) {
            return [
                'belong' => 'mobile',
                'os' => 'android',
            ];
        }elseif (str_contains($system, 'windows')) {
            return [
                'belong' => 'desktop',
                'os' => 'windows',
            ];
        }elseif (str_contains($system, 'linux')) {
            return [
                'belong' => 'desktop',
                'os' => 'linux',
            ];
        }else {
            return [
                'belong' => 'unknown',
                'os' => 'unknown',
            ];
        }
    }
}