<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockDataImport\Business\Step;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Business\DataSet\ProductOfferStockDataSetInterface;
use Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface;

class ProductOfferReferenceToIdProductOfferStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferStockDataImport\Dependency\Facade\ProductOfferStockDataImportToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferStockDataImportToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productOfferReference = $dataSet[ProductOfferStockDataSetInterface::PRODUCT_OFFER_REFERENCE];

        if (!$productOfferReference) {
            return;
        }

        /** @var \Generated\Shared\Transfer\ProductOfferTransfer $productOffer */
        $productOffer = $this->productOfferFacade->find($productOfferReference)->getProductOffers()->offsetGet(0);

        if (!$productOffer) {
            return;
        }

        $dataSet[ProductOfferStockDataSetInterface::FK_PRODUCT_OFFER] = $productOffer->getIdProductOffer();
    }

    /**
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function createProductOfferCriteriaFilterTransfer(string $productOfferReference): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setProductOfferReference($productOfferReference);
    }
}
