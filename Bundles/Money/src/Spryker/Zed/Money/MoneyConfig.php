<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Money;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class MoneyConfig extends AbstractBundleConfig
{
    protected const TEMPLATE_PATH_MONEY_TABLE = '@Money/Form/Type/money_table.twig';

    /**
     * @return string
     */
    public function getMoneyTableTemplatePath(): string
    {
        return static::TEMPLATE_PATH_MONEY_TABLE;
    }
}
