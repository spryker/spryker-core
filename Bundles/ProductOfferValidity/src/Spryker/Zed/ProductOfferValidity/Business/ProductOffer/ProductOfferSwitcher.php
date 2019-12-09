<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business\ProductOffer;

use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\ProductOfferValidity\Dependency\Facade\ProductOfferValidityToProductOfferFacadeInterface;
use Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface;

class ProductOfferSwitcher implements ProductOfferSwitcherInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface
     */
    protected $productOfferValidityRepository;

    /**
     * @var \Spryker\Zed\ProductOfferValidity\Dependency\Facade\ProductOfferValidityToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferValidity\Persistence\ProductOfferValidityRepositoryInterface $productOfferValidityRepository
     * @param \Spryker\Zed\ProductOfferValidity\Dependency\Facade\ProductOfferValidityToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(
        ProductOfferValidityRepositoryInterface $productOfferValidityRepository,
        ProductOfferValidityToProductOfferFacadeInterface $productOfferFacade
    ) {
        $this->productOfferValidityRepository = $productOfferValidityRepository;
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @return void
     */
    public function updateProductOfferValidity(): void
    {
        $this->getTransactionHandler()->handleTransaction(function (): void {
            $this->activateProductOffers();
            $this->deactivateProductOffers();
        });
    }

    /**
     * @return void
     */
    protected function activateProductOffers(): void
    {
        $productOfferValidityCollectionTransfer = $this->productOfferValidityRepository->getProductOfferValiditiesBecomingActive();
        foreach ($productOfferValidityCollectionTransfer->getProductOfferValidities() as $productOfferValidityTransfer) {
            $this->productOfferFacade->activateProductOfferById($productOfferValidityTransfer->getIdProductOffer());
        }
    }

    /**
     * @return void
     */
    protected function deactivateProductOffers(): void
    {
        $productOfferValidityCollectionTransfer = $this->productOfferValidityRepository->getProductOfferValiditiesBecomingInActive();
        foreach ($productOfferValidityCollectionTransfer->getProductOfferValidities() as $productOfferValidityTransfer) {
            $this->productOfferFacade->deactivateProductOfferById($productOfferValidityTransfer->getIdProductOffer());
        }
    }
}
