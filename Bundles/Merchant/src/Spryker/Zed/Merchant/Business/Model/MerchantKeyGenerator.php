<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

class MerchantKeyGenerator implements MerchantKeyGeneratorInterface
{
    protected const DEFAULT_KEY_PREFIX = 'M-';

    /**
     * @var string
     */
    protected $keyPrefix;

    public function __construct()
    {
        $this->keyPrefix = static::DEFAULT_KEY_PREFIX;
    }

    /**
     * @param string $keyPrefix
     *
     * @return void
     */
    public function setKeyPrefix(string $keyPrefix): void
    {
        $this->keyPrefix = $keyPrefix;
    }

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function generateUniqueKey(string $prefix = ''): string
    {
        return $this->keyPrefix . hash('crc32b', uniqid($prefix, true));
    }
}
