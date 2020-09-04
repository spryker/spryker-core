<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationChecker;
use Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationCheckerInterface;

class ProductConfigurationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationCheckerInterface
     */
    public function createQuoteProductConfigurationChecker(): QuoteProductConfigurationCheckerInterface
    {
        return new QuoteProductConfigurationChecker();
    }
}
