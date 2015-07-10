<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

use SprykerFeature\Shared\System\SystemConfig;

class StaticUrlGenerator
{

    const STATIC_ASSETS = 'static_assets';
    const STATIC_MEDIA = 'static_media';

    /**
     * @param string $path
     * @param bool $ssl
     *
     * @return string
     * @static
     */
    public static function getStaticMediaUrl($path, $ssl = false)
    {
        return self::getStaticUrl(self::STATIC_MEDIA, $path, $ssl);
    }

    /**
     * @param string $path
     * @param bool $ssl
     *
     * @return string
     * @static
     */
    public static function getStaticAssetsUrl($path, $ssl = false)
    {
        return self::getStaticUrl(self::STATIC_ASSETS, $path, $ssl);
    }

    /**
     * @param string $type
     * @param string $path
     * @param bool $ssl
     *
     * @return string
     * @static
     */
    protected static function getStaticUrl($type, $path, $ssl)
    {
        if (Cloud::isCloudStorageCdnEnabled()) {
            return static::getStaticCdnUrl($type, $path, $ssl);
        } else {

            if($type === self::STATIC_ASSETS && $ssl){
                $urlPart = Config::get(SystemConfig::HOST_SSL_STATIC_ASSETS);
            }elseif($type === self::STATIC_ASSETS && !$ssl){
                $urlPart = Config::get(SystemConfig::HOST_STATIC_ASSETS);
            }elseif($type === self::STATIC_MEDIA && $ssl){
                $urlPart = Config::get(SystemConfig::HOST_SSL_STATIC_MEDIA);
            }else{
                $urlPart = Config::get(SystemConfig::HOST_STATIC_MEDIA);
            }

            return '//' . $urlPart . (($path[0] === '/') ? '' : '/') . $path;
        }
    }

    /**
     * @param string $type
     * @param string $path
     * @param bool $ssl
     *
     * @return string
     * @static
     */
    protected static function getStaticCdnUrl($type, $path, $ssl)
    {
        if ($ssl) {
            $host = Config::get(SystemConfig::HOST_SSL_YVES);
        } else {
            $host = Config::get(SystemConfig::HOST_YVES);
        }

        if($type === self::STATIC_ASSETS && $ssl){
            $urlPart = Config::get(SystemConfig::CLOUD_CDN_STATIC_ASSETS_HTTPS);
        }elseif($type === self::STATIC_ASSETS && !$ssl){
            $urlPart = Config::get(SystemConfig::CLOUD_CDN_STATIC_ASSETS_HTTP);
        }elseif($type === self::STATIC_MEDIA && $ssl){
            $urlPart = Config::get(SystemConfig::CLOUD_CDN_STATIC_MEDIA_HTTPS);
        }else{
            $urlPart = Config::get(SystemConfig::CLOUD_CDN_STATIC_MEDIA_HTTP);
        }

        return $host . '/' . $urlPart . (($path[0] === '/') ? '' : '/') . $path;
    }

}
