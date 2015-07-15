<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Catalog\Code\Storage;

use SprykerFeature\Shared\Library\Storage\StorageKeyGenerator as BaseStorageKeyGenerator;

class StorageKeyGenerator extends BaseStorageKeyGenerator
{

    const KEY_NAMESPACE = 'catalog';

    /**
     * @param int $id
     *
     * @return string
     * @static
     */
    public static function getProductKey($id)
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'product', $id]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @param string $sku
     *
     * @return string
     * @static
     */
    public static function getProductSkuKey($sku)
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'sku', 'product', $sku]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @return string
     * @static
     */
    public static function getProductOptionKey()
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'options']);

        return self::prependStoreName($key);
    }

    /**
     * @param string $url
     *
     * @return string
     * @static
     */
    public static function getProductUrlKey($url)
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'urlkey', $url]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @param string $brandName
     *
     * @return string
     * @static
     */
    public static function getBrandKey($brandName)
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'brand', $brandName]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @return string
     * @static
     */
    public static function getBrandListKey()
    {
        $key = implode(self::KEY_SEPERATOR, [self::KEY_NAMESPACE, 'list', 'brand']);

        return self::prependStoreName($key);
    }

}
