<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantOms;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MerchantOmsConfig extends AbstractBundleConfig
{
    public const MERCHANT_OMS_STATE_MACHINE_NAME = 'Merchant';

    protected const MERCHANT_STATE_MACHINE_INITIAL_STATE = 'created';

    protected const MERCHANT_OMS_DEFAULT_PROCESS_NAME = 'MerchantDefaultStateMachine';

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantOmsDefaultProcessName(): string
    {
        return static::MERCHANT_OMS_DEFAULT_PROCESS_NAME;
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getMerchantOmsProcesses(): array
    {
        return [
            $this->getMerchantOmsDefaultProcessName(),
        ];
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getMerchantProcessInitialStateMap(): array
    {
        return [
            $this->getMerchantOmsDefaultProcessName() => static::MERCHANT_STATE_MACHINE_INITIAL_STATE,
        ];
    }
}
