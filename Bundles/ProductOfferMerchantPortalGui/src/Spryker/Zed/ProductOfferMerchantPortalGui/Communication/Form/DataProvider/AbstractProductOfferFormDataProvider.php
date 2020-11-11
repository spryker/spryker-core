<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\MerchantStockCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductOfferStockTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\DefaultMerchantStockNotFoundException;
use Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Form\ProductOfferForm;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface;
use Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface;

abstract class AbstractProductOfferFormDataProvider
{
    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @var \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface
     */
    protected $merchantStockFacade;

    /**
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToProductFacadeInterface $productFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     * @param \Spryker\Zed\ProductOfferMerchantPortalGui\Dependency\Facade\ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade
     */
    public function __construct(
        ProductOfferMerchantPortalGuiToProductFacadeInterface $productFacade,
        ProductOfferMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade,
        ProductOfferMerchantPortalGuiToMerchantStockFacadeInterface $merchantStockFacade
    ) {
        $this->productFacade = $productFacade;
        $this->merchantUserFacade = $merchantUserFacade;
        $this->merchantStockFacade = $merchantStockFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int[][]
     */
    public function getOptions(ProductAbstractTransfer $productAbstractTransfer): array
    {
        return [
            ProductOfferForm::OPTION_STORE_CHOICES => $this->getStoreChoices($productAbstractTransfer),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return int[]
     */
    protected function getStoreChoices(ProductAbstractTransfer $productAbstractTransfer): array
    {
        $storeChoices = [];

        $storeRelationTransfer = $productAbstractTransfer->getStoreRelation();

        if (!$storeRelationTransfer) {
            return $storeChoices;
        }

        foreach ($storeRelationTransfer->getStores() as $storeTransfer) {
            $storeChoices[$storeTransfer->getName()] = $storeTransfer->getIdStore();
        }

        return $storeChoices;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @throws \Spryker\Zed\ProductOfferMerchantPortalGui\Communication\Exception\DefaultMerchantStockNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    protected function setDefaultMerchantStock(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $merchantStockCriteriaTransfer = (new MerchantStockCriteriaTransfer())
            ->setIsDefault(true)
            ->setIdMerchant($productOfferTransfer->getFkMerchant());
        $stockTransfers = $this->merchantStockFacade->get($merchantStockCriteriaTransfer)->getStocks();

        if (!$stockTransfers->count()) {
            throw new DefaultMerchantStockNotFoundException(sprintf(
                'Default Merchant stock not found by Merchant ID `%s`',
                $productOfferTransfer->getFkMerchant()
            ));
        }

        if (!$productOfferTransfer->getProductOfferStocks()->count()) {
            $productOfferStockTransfer = (new ProductOfferStockTransfer())->setStock($stockTransfers->offsetGet(0));
            $productOfferTransfer->addProductOfferStock($productOfferStockTransfer);

            return $productOfferTransfer;
        }

        foreach ($productOfferTransfer->getProductOfferStocks() as $productOfferStockTransfer) {
            if ($productOfferStockTransfer->getStock()->getIdStock() === $stockTransfers->offsetGet(0)->getIdStock()) {
                $productOfferTransfer->setProductOfferStocks(new ArrayObject([$productOfferStockTransfer]));

                break;
            }
        }

        return $productOfferTransfer;
    }
}
