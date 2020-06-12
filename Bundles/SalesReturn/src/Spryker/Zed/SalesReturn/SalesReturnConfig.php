<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesReturnConfig extends AbstractBundleConfig
{
    protected const EVENT_START_RETURN = 'start-return';
    protected const GLOBAL_RETURNABLE_NUMBER_OF_DAYS = 30;

    protected const RETURNABLE_STATE_NAMES = [
        'shipped',
        'delivered',
    ];

    /**
     * @api
     *
     * @return string
     */
    public function getReturnReferenceFormat(): string
    {
        return '%s-R%s';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getGuestReturnReferenceFormat(): string
    {
        return '%s-G%s';
    }

    /**
     * @api
     *
     * @return string[]
     */
    public function getReturnableStateNames(): array
    {
        return static::RETURNABLE_STATE_NAMES;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getGlobalReturnableNumberOfDays(): int
    {
        return static::GLOBAL_RETURNABLE_NUMBER_OF_DAYS;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStartReturnEvent(): string
    {
        return static::EVENT_START_RETURN;
    }
}
