<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantOms\Mocks;

use Spryker\Zed\MerchantOms\MerchantOmsConfig as SprykerMerchantOmsConfig;

class MerchantOmsConfigMock extends SprykerMerchantOmsConfig
{
    /**
     * @var string
     */
    protected $merchantOmsDefaultProcessName = 'Test01';

    /**
     * @return string
     */
    public function getMerchantOmsDefaultProcessName(): string
    {
        return $this->merchantOmsDefaultProcessName;
    }

    /**
     * @param string $merchantOmsDefaultProcessName
     *
     * @return void
     */
    public function setMerchantOmsDefaultProcessName(string $merchantOmsDefaultProcessName): void
    {
        $this->merchantOmsDefaultProcessName = $merchantOmsDefaultProcessName;
    }
}
