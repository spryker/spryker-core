<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business;

use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdder;
use Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ItemExpander;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ItemExpanderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpander;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpanderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Merger\ItemMerger;
use Spryker\Zed\ClickAndCollectExample\Business\Merger\ItemMergerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\DeliveryProductOfferReplacementChecker;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\PickupProductOfferReplacementChecker;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinder;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReader;
use Spryker\Zed\ClickAndCollectExample\Business\Reader\ProductOfferServicePointReaderInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Replacer\DeliveryItemProductOfferReplacer;
use Spryker\Zed\ClickAndCollectExample\Business\Replacer\ItemProductOfferReplacerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Replacer\PickupItemProductOfferReplacer;
use Spryker\Zed\ClickAndCollectExample\Business\Replacer\QuoteProductOfferReplacer;
use Spryker\Zed\ClickAndCollectExample\Business\Replacer\QuoteProductOfferReplacerInterface;
use Spryker\Zed\ClickAndCollectExample\Business\Validator\QuoteItemProductOfferReplacementValidator;
use Spryker\Zed\ClickAndCollectExample\Business\Validator\QuoteItemProductOfferReplacementValidatorInterface;
use Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleDependencyProvider;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToStoreFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\ClickAndCollectExample\ClickAndCollectExampleConfig getConfig()
 * @method \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface getRepository()
 */
class ClickAndCollectExampleBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Replacer\ItemProductOfferReplacerInterface
     */
    public function createPickupItemProductOfferReplacer(): ItemProductOfferReplacerInterface
    {
        return new PickupItemProductOfferReplacer(
            $this->createProductOfferServicePointReader(),
            $this->createPickupProductOfferReplacementFinder(),
            $this->createQuoteReplacementResponseErrorAdder(),
            $this->createItemMerger(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Replacer\ItemProductOfferReplacerInterface
     */
    public function createDeliveryItemProductOfferReplacer(): ItemProductOfferReplacerInterface
    {
        return new DeliveryItemProductOfferReplacer(
            $this->createProductOfferServicePointReader(),
            $this->createDeliveryProductOfferReplacementFinder(),
            $this->createQuoteReplacementResponseErrorAdder(),
            $this->createItemMerger(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Replacer\QuoteProductOfferReplacerInterface
     */
    public function createQuoteProductOfferReplacer(): QuoteProductOfferReplacerInterface
    {
        return new QuoteProductOfferReplacer(
            $this->getQuoteItemReplacers(),
        );
    }

    /**
     * @return list<\Spryker\Zed\ClickAndCollectExample\Business\Replacer\ItemProductOfferReplacerInterface>
     */
    public function getQuoteItemReplacers(): array
    {
        return [
            $this->createPickupItemProductOfferReplacer(),
            $this->createDeliveryItemProductOfferReplacer(),
        ];
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
            $this->getStoreFacade(),
            $this->getAvailabilityFacade(),
            $this->createPickupProductOfferReplacementChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder\ProductOfferReplacementFinderInterface
     */
    public function createDeliveryProductOfferReplacementFinder(): ProductOfferReplacementFinderInterface
    {
        return new ProductOfferReplacementFinder(
            $this->getStoreFacade(),
            $this->getAvailabilityFacade(),
            $this->createDeliveryProductOfferReplacementChecker(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\ErrorAdder\QuoteReplacementResponseErrorAdderInterface
     */
    public function createQuoteReplacementResponseErrorAdder(): QuoteReplacementResponseErrorAdderInterface
    {
        return new QuoteReplacementResponseErrorAdder();
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

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Validator\QuoteItemProductOfferReplacementValidatorInterface
     */
    public function createQuoteItemProductOfferReplacementValidator(): QuoteItemProductOfferReplacementValidatorInterface
    {
        return new QuoteItemProductOfferReplacementValidator(
            $this->createItemExpander(),
            $this->createQuoteProductOfferReplacer(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Expander\ItemExpanderInterface
     */
    public function createItemExpander(): ItemExpanderInterface
    {
        return new ItemExpander(
            $this->getServicePointFacade(),
            $this->getShipmentFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Business\Merger\ItemMergerInterface
     */
    public function createItemMerger(): ItemMergerInterface
    {
        return new ItemMerger();
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToServicePointFacadeInterface
     */
    public function getServicePointFacade(): ClickAndCollectExampleToServicePointFacadeInterface
    {
        return $this->getProvidedDependency(ClickAndCollectExampleDependencyProvider::FACADE_SERVICE_POINT);
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToShipmentFacadeInterface
     */
    public function getShipmentFacade(): ClickAndCollectExampleToShipmentFacadeInterface
    {
        return $this->getProvidedDependency(ClickAndCollectExampleDependencyProvider::FACADE_SHIPMENT);
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToStoreFacadeInterface
     */
    public function getStoreFacade(): ClickAndCollectExampleToStoreFacadeInterface
    {
        return $this->getProvidedDependency(ClickAndCollectExampleDependencyProvider::FACADE_STORE);
    }

    /**
     * @return \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface
     */
    public function getAvailabilityFacade(): ClickAndCollectExampleToAvailabilityFacadeInterface
    {
        return $this->getProvidedDependency(ClickAndCollectExampleDependencyProvider::FACADE_AVAILABILITY);
    }
}
