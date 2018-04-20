<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityOfferConnector\Business\Model;

use Generated\Shared\Transfer\OfferTransfer;
use Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToAvailabilityFacadeInterface;
use Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToStoreFacadeInterface;

class OfferQuoteItemStockHydrator implements OfferQuoteItemStockHydratorInterface
{
    /**
     * @var \Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToAvailabilityFacadeInterface
     */
    protected $availabilityFacade;

    /**
     * @param \Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\AvailabilityOfferConnector\Dependency\Facade\AvailabilityOfferConnectorToAvailabilityFacadeInterface $availabilityFacade
     */
    public function __construct(
        AvailabilityOfferConnectorToStoreFacadeInterface $storeFacade,
        AvailabilityOfferConnectorToAvailabilityFacadeInterface $availabilityFacade
    ) {
        $this->storeFacade = $storeFacade;
        $this->availabilityFacade = $availabilityFacade;
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
            $stock = $this->availabilityFacade
                ->calculateStockForProductWithStore(
                    $itemTransfer->getSku(),
                    $storeTransfer
                );

            $itemTransfer->setStock($stock);
        }

        return $offerTransfer;
    }
}
