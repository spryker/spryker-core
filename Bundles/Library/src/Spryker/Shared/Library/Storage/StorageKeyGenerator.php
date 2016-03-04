<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage;

use Spryker\Shared\Kernel\Store;

abstract class StorageKeyGenerator
{

    const KEY_SEPARATOR = '.';

    /**
     * @param string $key
     *
     * @return string
     */
    protected static function escapeKey($key)
    {
        $charsToReplace = ['"', "'", ' ', "\0", "\n", "\r"];

        return str_replace($charsToReplace, '-', mb_strtolower(trim($key)));
    }

    /**
     * @param string $key
     *
     * @return string
     */
    protected static function prependStoreName($key)
    {
        $storeName = Store::getInstance()->getStoreName();

        return $storeName . self::KEY_SEPARATOR . $key;
    }

}
