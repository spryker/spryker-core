<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Shared\ClickAndCollectExample;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class ClickAndCollectExampleConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @uses \Spryker\Shared\Calculation\CalculationPriceMode::PRICE_MODE_GROSS
     *
     * @var string
     */
    public const PRICE_MODE_GROSS = 'GROSS_MODE';
}
