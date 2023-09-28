<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Client\ClickAndCollectExample;

use Spryker\Client\ClickAndCollectExample\Calculator\ProductOfferServicePointAvailabilityCalculator;
use Spryker\Client\ClickAndCollectExample\Calculator\ProductOfferServicePointAvailabilityCalculatorInterface;
use Spryker\Client\ClickAndCollectExample\Sorter\ProductOfferServicePointAvailabilityResponseItemSorter;
use Spryker\Client\ClickAndCollectExample\Sorter\ProductOfferServicePointAvailabilityResponseItemSorterInterface;
use Spryker\Client\Kernel\AbstractFactory;

class ClickAndCollectExampleFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ClickAndCollectExample\Calculator\ProductOfferServicePointAvailabilityCalculatorInterface
     */
    public function createProductOfferServicePointAvailabilityCalculator(): ProductOfferServicePointAvailabilityCalculatorInterface
    {
        return new ProductOfferServicePointAvailabilityCalculator(
            $this->createProductOfferServicePointAvailabilityResponseItemSorter(),
        );
    }

    /**
     * @return \Spryker\Client\ClickAndCollectExample\Sorter\ProductOfferServicePointAvailabilityResponseItemSorterInterface
     */
    public function createProductOfferServicePointAvailabilityResponseItemSorter(): ProductOfferServicePointAvailabilityResponseItemSorterInterface
    {
        return new ProductOfferServicePointAvailabilityResponseItemSorter();
    }
}
