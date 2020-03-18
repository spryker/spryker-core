<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class SalesReturnConfig extends AbstractBundleConfig
{
    protected const EVENT_RETURN = 'return';
    protected const RETURNABLE_NUMBER_OF_DAYS = 30;

    protected const RETURNABLE_STATE_NAMES = [
        'shipped',
        'delivered',
    ];

    /**
     * @return string
     */
    public function getReturnReferenceFormat(): string
    {
        return '%s-R%s';
    }

    /**
     * @return string[]
     */
    public function getReturnableStateNames(): array
    {
        return static::RETURNABLE_STATE_NAMES;
    }

    /**
     * @return int
     */
    public function getReturnableNumberOfDays(): int
    {
        return static::RETURNABLE_NUMBER_OF_DAYS;
    }

    /**
     * @return string
     */
    public function getReturnEvent(): string
    {
        return static::EVENT_RETURN;
    }
}
