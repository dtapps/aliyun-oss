<?php

// +----------------------------------------------------------------------
// | ThinkPHP6阿里云存储 for ThinkLibrary 6.0
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 [ https://www.dtapp.net ]
// +----------------------------------------------------------------------
// | 官方网站: https://www.dtapp.net
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | 国内仓库地址 ：https://gitee.com/dtapps/aliyun-oss
// | 国外仓库地址 ：https://github.com/dtapps/aliyun-oss
// | Packagist 地址 ：https://packagist.org/packages/dtapps/aliyun-oss
// +----------------------------------------------------------------------

namespace DtApp\AliYun\aliyun;

use DtApp\ThinkLibrary\Service;
use OSS\Core\OssException;
use OSS\OssClient;

/**
 * 定义当前版本
 */
const VERSION = '1.0.0';

/**
 * 阿里云对象存储
 * https://www.aliyun.com/product/oss
 * Class OssService
 * @package DtApp\AliYun\aliyun
 */
class OssService extends Service
{
    private $accessKeyId, $accessKeySecret, $endpoint, $bucket;

    public function accessKeyId(string $accessKeyId)
    {
        $this->accessKeyId = $accessKeyId;
        return $this;
    }

    public function accessKeySecret(string $accessKeySecret)
    {
        $this->accessKeySecret = $accessKeySecret;
        return $this;
    }

    public function endpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    public function bucket(string $bucket)
    {
        $this->bucket = $bucket;
        return $this;
    }

    /**
     * 获取配置信息
     * @return \DtApp\ThinkLibrary\service\aliyun\OssService
     */
    private function getConfig()
    {
        $this->accessKeyId = config('dtapp_aliyun.oss.access_key_id');
        $this->accessKeySecret = config('dtapp_aliyun.oss.access_key_secret');
        $this->endpoint = config('dtapp_aliyun.oss.endpoint');
        $this->bucket = config('dtapp_aliyun.oss.bucket');
        return $this;
    }

    /**
     * 上传文件
     * @param $object
     * @param $filePath
     * @return bool|string
     */
    public function upload(string $object, string $filePath)
    {
        if (empty($this->accessKeySecret) || empty($this->accessKeySecret) || empty($this->endpoint) || empty($this->bucket)) {
            $this->getConfig();
        }
        try {
            $ossClient = new OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
            $ossClient->uploadFile($this->bucket, $object, $filePath);
            return config('dtapp_aliyun.oss.url', '') . $object;
        } catch (OssException $e) {
            return $e->getMessage();
        }
    }
}