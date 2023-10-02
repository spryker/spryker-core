<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business;

use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdder;
use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpander;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpanderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\DeliveryProductOfferReplacementChecker;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\PickupProductOfferReplacementChecker;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinder;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\QuoteItemReplacer\DeliveryQuoteItemReplacer;
use Spryker\Zed\ClickAndCollectExample\Business\QuoteItemReplacer\PickupQuoteItemReplacer;
use Spryker\Zed\ClickAndCollectExample\Business\QuoteItemReplacer\QuoteItemReplacerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReader;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig getConfig()
 * @method \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface getRepository()
 */
class ClickAndCollectExampleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\QuoteItemReplacer\QuoteItemReplacerInterface
     */
    public function createPickupQuoteItemReplacer(): QuoteItemReplacerInterface
    {
        return new PickupQuoteItemReplacer(
            $this->createProductOfferServicePointReader(),
            $this->createPickupProductOfferReplacementFinder(),
            $this->createQuoteResponseErrorAdder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\QuoteItemReplacer\QuoteItemReplacerInterface
     */
    public function createDeliveryQuoteItemReplacer(): QuoteItemReplacerInterface
    {
        return new DeliveryQuoteItemReplacer(
            $this->createProductOfferServicePointReader(),
            $this->createDeliveryProductOfferReplacementFinder(),
            $this->createQuoteResponseErrorAdder(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface
     */
    public function createProductOfferServicePointReader(): ProductOfferServicePointReaderInterface
    {
        return new ProductOfferServicePointReader(
            $this->getRepository(),
            $this->createProductOfferServicePointExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpanderInterface
     */
    public function createProductOfferServicePointExpander(): ProductOfferServicePointExpanderInterface
    {
        return new ProductOfferServicePointExpander(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface
     */
    public function createPickupProductOfferReplacementFinder(): ProductOfferReplacementFinderInterface
    {
        return new ProductOfferReplacementFinder(
            $this->createPickupProductOfferReplacementChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface
     */
    public function createDeliveryProductOfferReplacementFinder(): ProductOfferReplacementFinderInterface
    {
        return new ProductOfferReplacementFinder(
            $this->createDeliveryProductOfferReplacementChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteResponseErrorAdderInterface
     */
    public function createQuoteResponseErrorAdder(): QuoteResponseErrorAdderInterface
    {
        return new QuoteResponseErrorAdder();
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface
     */
    public function createPickupProductOfferReplacementChecker(): ProductOfferReplacementCheckerInterface
    {
        return new PickupProductOfferReplacementChecker();
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface
     */
    public function createDeliveryProductOfferReplacementChecker(): ProductOfferReplacementCheckerInterface
    {
        return new DeliveryProductOfferReplacementChecker();
    }
}
