<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\Model;

interface MerchantKeyGeneratorInterface
{
    /**
     * @param string $keyPrefix
     *
     * @return void
     */
    public function setKeyPrefix(string $keyPrefix): void;

    /**
     * @param string $prefix
     *
     * @return string
     */
    public function generateUniqueKey(string $prefix = ''): string;
}
