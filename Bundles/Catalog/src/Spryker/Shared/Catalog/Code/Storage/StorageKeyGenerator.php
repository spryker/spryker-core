<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Catalog\Code\Storage;

use Spryker\Shared\Library\Storage\StorageKeyGenerator as BaseStorageKeyGenerator;

/**
 * @deprecated This Class is not used any more and will be removed.
 */
class StorageKeyGenerator extends BaseStorageKeyGenerator
{

    const KEY_NAMESPACE = 'catalog';

    /**
     * @param int $id
     *
     * @return string
     */
    public static function getProductKey($id)
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'product', $id]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    public static function getProductSkuKey($sku)
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'sku', 'product', $sku]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @return string
     */
    public static function getProductOptionKey()
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'options']);

        return self::prependStoreName($key);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    public static function getProductUrlKey($url)
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'urlkey', $url]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @param string $brandName
     *
     * @return string
     */
    public static function getBrandKey($brandName)
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'brand', $brandName]);

        return self::prependStoreName(self::escapeKey($key));
    }

    /**
     * @return string
     */
    public static function getBrandListKey()
    {
        $key = implode(self::KEY_SEPARATOR, [self::KEY_NAMESPACE, 'list', 'brand']);

        return self::prependStoreName($key);
    }

}
