<?php

namespace Spryker\Zed\Availability\Business\Model\Hydrator;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\Availability\Business\Model\SellableInterface;
use Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface;

class OfferQuoteItemStockHydrator implements OfferQuoteItemStockHydratorInterface
{
    /**
     * @var \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\Availability\Business\Model\SellableInterface
     */
    protected $sellableModel;

    /**
     * @param \Spryker\Zed\Availability\Dependency\Facade\AvailabilityToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\Availability\Business\Model\SellableInterface $sellableModel
     */
    public function __construct(
        AvailabilityToStoreFacadeInterface $storeFacade,
        SellableInterface $sellableModel
    ) {
        $this->storeFacade = $storeFacade;
        $this->sellableModel = $sellableModel;
    }

    /**
     * @param \Generated\Shared\Transfer\OfferTransfer $offerTransfer
     *
     * @return \Generated\Shared\Transfer\OfferTransfer
     */
    public function hydrate(OfferTransfer $offerTransfer): OfferTransfer
    {
        $offerTransfer->requireQuote();

        $quoteTransfer = $offerTransfer->getQuote();
        $storeTransfer = $quoteTransfer->getStore();

        $storeTransfer = $this->storeFacade
            ->getStoreByName($storeTransfer->getName());

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            $stock = $this->sellableModel
                ->calculateStockForProductWithStore(
                    $itemTransfer->getSku(),
                    $storeTransfer
                );

            $itemTransfer->setStock($stock);
        }

        return $offerTransfer;
    }
}
