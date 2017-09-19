<?php
namespace Wxapp\Controller;

use Common\Controller\Base;
use Wxapp\Service\QrcodeService;

class QrcodeController extends Base {
    public function getwxacode() {
        //字节返回二维码图片格式
        header('Content-Type:image/png');
        $res = QrcodeService::getwxacode('/pages/index/index', '480');
        echo $res;
    }

    public function getwxacodeunlimit() {
        //字节返回二维码图片格式
        header('Content-Type:image/png');
        $res = QrcodeService::getwxacodeunlimit('22', '480');
        echo $res;
    }

    public function createwxaqrcode() {
        //字节返回二维码图片格式
        header('Content-Type:image/png');
        $res = QrcodeService::createwxaqrcode('/pages/index/index', '480');
        echo $res;
    }
}