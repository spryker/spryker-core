<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidity\Business\Switcher;

use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer;
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
        $productOfferValidityCollectionTransfer = $this->productOfferValidityRepository->getActivatableProductOffers();
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())->setProductOfferIds(
            $this->getProductOfferIds($productOfferValidityCollectionTransfer),
        );
        $productOfferCollectionTransfer = $this->productOfferFacade->get($productOfferCriteriaTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferTransfer->setIsActive(true);
            $this->productOfferFacade->update($productOfferTransfer);
        }
    }

    /**
     * @return void
     */
    protected function deactivateProductOffers(): void
    {
        $productOfferValidityCollectionTransfer = $this->productOfferValidityRepository->getDeactivatableProductOffers();
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())->setProductOfferIds(
            $this->getProductOfferIds($productOfferValidityCollectionTransfer),
        );
        $productOfferCollectionTransfer = $this->productOfferFacade->get($productOfferCriteriaTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferTransfer->setIsActive(false);
            $this->productOfferFacade->update($productOfferTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferValidityCollectionTransfer $productOfferValidityCollectionTransfer
     *
     * @return array<int>
     */
    protected function getProductOfferIds(ProductOfferValidityCollectionTransfer $productOfferValidityCollectionTransfer): array
    {
        $productOfferIds = [];

        foreach ($productOfferValidityCollectionTransfer->getProductOfferValidities() as $productOfferValidity) {
            $productOfferIds[] = $productOfferValidity->getIdProductOffer();
        }

        return $productOfferIds;
    }
}
