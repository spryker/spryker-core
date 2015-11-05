<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library;

use SprykerEngine\Shared\Kernel\Store;
use SprykerFeature\Shared\ProductImage\ProductImageConfig;
use SprykerFeature\Shared\System\SystemConfig;

class Image
{

    /*
     * @todo
     */
    const PLACEHOLDER_PRODUCT_ZED = '/new/images/product/default-product-image.jpg';
    const PLACEHOLDER_PRODUCT_YVES = '/images/product/default.png';

    const HTTPS = 'https';
    const HTTP = 'http';

    const POSITION_HASH = 0;
    const POSITION_SIZE = 1;
    const POSITION_WEIGHT = 2;
    const POSITION_DIRECTORY = 3;

    const ORDER_WEIGHT_ASC = 'asc';
    const ORDER_WEIGHT_DESC = 'desc';

    const SIZE_XXL = 'xxl';
    const SIZE_XL = 'xl';
    const SIZE_L = 'l';
    const SIZE_M = 'm';
    const SIZE_S = 's';
    const SIZE_XS = 'xs';

    protected static $imageBaseUrl;
    protected static $config;

    /**
     * @param string $filename
     *
     * @return string
     */
    public static function getAbsoluteProductImageUrl($filename)
    {
        $urlDomain = self::getStaticMediaUrl();

        if ($filename === '') {
            $applicationName = APPLICATION;

            switch ($applicationName) {
                case 'ZED':
                    return self::getSchema() . Config::get(SystemConfig::HOST_ZED_GUI) . self::PLACEHOLDER_PRODUCT_ZED;
                    break;

                case 'YVES':
                    return self::getSchema() . Config::get(SystemConfig::HOST_YVES) . self::PLACEHOLDER_PRODUCT_YVES;
                    break;
            }
        } else {
            $urlKey = $filename;
        }

        /* ONLY FOR CLOUD HOSTING SETUP USED */
        if (Cloud::isCloudStorageCdnEnabled()) {
            return self::getAbsoluteProductImageUrlForCloudUsage($urlKey);
        }

        return self::getSchema() . implode(
            '/',
            [
                $urlDomain,
                Store::getInstance()->getStoreName(),
                Config::get(ProductImageConfig::PRODUCT_IMAGE_IMAGE_URL_PREFIX), $urlKey,
            ]

        );
    }

    /**
     * @param string $objectName
     *
     * @return string
     */
    protected static function getAbsoluteProductImageUrlForCloudUsage($objectName)
    {
        if (static::getProtocol() === self::HTTP) {
            $host = Config::get(SystemConfig::CLOUD_CDN_STATIC_MEDIA_HTTP);
        } else {
            $host = Config::get(SystemConfig::CLOUD_CDN_STATIC_MEDIA_HTTPS);
        }

        return $host . '/' . Config::get(SystemConfig::CLOUD_CDN_STATIC_MEDIA_PREFIX)
            . Config::get(SystemConfig::CLOUD_CDN_PRODUCT_IMAGES_PATH_NAME) . $objectName;
    }

    /**
     * @param array $images
     * @param string $order
     *
     * @throws \ErrorException
     *
     * @return array
     */
    public static function getGroupedImagesBySize(array $images, $order = self::ORDER_WEIGHT_ASC)
    {
        $virtualDirectory = null;
        $groupedImages = [];

        foreach ($images as $seoFilename) {
            $parts = array_reverse(explode('-', $seoFilename));

            if ($virtualDirectory === null) {
                $virtualDirectory = $parts[self::POSITION_DIRECTORY];
            } else {
                if ($virtualDirectory !== $parts[self::POSITION_DIRECTORY]) {
                    throw new \ErrorException('You cannot mix product images from different products');
                }
            }

            if (array_key_exists($parts[self::POSITION_SIZE], $groupedImages)) {
                $groupedImages[$parts[self::POSITION_SIZE]][$parts[self::POSITION_WEIGHT]] = $seoFilename;
            } else {
                $groupedImages[$parts[self::POSITION_SIZE]] = [$parts[self::POSITION_WEIGHT] => $seoFilename];
            }
        }

        foreach ($groupedImages as &$images) {
            ksort($images);
            if ($order === self::ORDER_WEIGHT_DESC) {
                $images = array_reverse($images, true);
            }
        }

        return $groupedImages;
    }

    /**
     * @param array $groupedImageFileNames
     * @param string $size
     *
     * @return mixed|string
     */
    public static function getFirstProductImageFilenameBySize(array $groupedImageFileNames, $size = self::SIZE_L)
    {
        if (!array_key_exists($size, $groupedImageFileNames)) {
            return '';
        } else {
            return array_shift($groupedImageFileNames[$size]);
        }
    }

    /**
     * @param array $groupedImageFileNames
     * @param string $size
     *
     * @return array
     */
    public static function getAllProductImagesBySize(array $groupedImageFileNames, $size = self::SIZE_L)
    {
        if (!array_key_exists($size, $groupedImageFileNames)) {
            return [];
        } else {
            return $groupedImageFileNames[$size];
        }
    }

    /**
     * @return string
     */
    protected static function getStaticMediaUrl()
    {
        if (self::HTTPS === self::getProtocol()) {
            return Config::get(SystemConfig::HOST_SSL_STATIC_MEDIA);
        }

        return Config::get(SystemConfig::HOST_STATIC_MEDIA);
    }

    /**
     * @return string
     */
    protected static function getProtocol()
    {
        if (isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] === 'on' || $_SERVER['HTTPS'] === 1)
            || isset($_SERVER['X-Forwarded-Proto']) && $_SERVER['X-Forwarded-Proto'] === 'https'
        ) {
            return self::HTTPS;
        }

        return self::HTTP;
    }

    /**
     * @return string
     */
    protected static function getSchema()
    {
        $protocol = self::getProtocol();

        return $protocol . '://';
    }

}
