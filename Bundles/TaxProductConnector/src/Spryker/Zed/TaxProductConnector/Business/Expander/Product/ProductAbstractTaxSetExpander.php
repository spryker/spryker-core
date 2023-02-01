<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxProductConnector\Business\Expander\Product;

use Generated\Shared\Transfer\ProductAbstractCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractCriteriaTransfer;
use Generated\Shared\Transfer\ProductAbstractTaxSetCollectionTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface;

class ProductAbstractTaxSetExpander implements ProductAbstractTaxSetExpanderInterface
{
    /**
     * @var \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface
     */
    protected $taxProductConnectorRepository;

    /**
     * @param \Spryker\Zed\TaxProductConnector\Persistence\TaxProductConnectorRepositoryInterface $taxProductConnectorRepository
     */
    public function __construct(TaxProductConnectorRepositoryInterface $taxProductConnectorRepository)
    {
        $this->taxProductConnectorRepository = $taxProductConnectorRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function expand(ProductAbstractTransfer $productAbstractTransfer): ProductAbstractTransfer
    {
        $productAbstractTransfer->requireIdProductAbstract();

        $taxSetTransfer = $this->taxProductConnectorRepository->findByIdProductAbstract($productAbstractTransfer->getIdProductAbstract());

        if ($taxSetTransfer === null) {
            return $productAbstractTransfer;
        }

        return $productAbstractTransfer->setIdTaxSet($taxSetTransfer->getIdTaxSet());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractCollectionTransfer $productAbstractCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractCollectionTransfer
     */
    public function expandProductAbstractCollection(
        ProductAbstractCollectionTransfer $productAbstractCollectionTransfer,
        ProductAbstractCriteriaTransfer $productAbstractCriteriaTransfer
    ): ProductAbstractCollectionTransfer {
        if (!$productAbstractCriteriaTransfer->getProductAbstractRelations()->getWithTaxSet()) {
            return $productAbstractCollectionTransfer;
        }

        $productAbstractIds = [];
        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            $productAbstractIds[] = $productAbstractTransfer->getIdProductAbstractOrFail();
        }

        $taxSetTransfers = $this->taxProductConnectorRepository->getTaxSets($productAbstractIds);

        if (!count($taxSetTransfers)) {
            return $productAbstractCollectionTransfer;
        }

        foreach ($productAbstractCollectionTransfer->getProductAbstracts() as $productAbstractTransfer) {
            if (isset($taxSetTransfers[$productAbstractTransfer->getIdProductAbstractOrFail()])) {
                $productAbstractCollectionTransfer->addProductTaxSet(
                    (new ProductAbstractTaxSetCollectionTransfer())
                        ->setProductAbstractSku($productAbstractTransfer->getSkuOrFail())
                        ->setTaxSet($taxSetTransfers[$productAbstractTransfer->getIdProductAbstractOrFail()]),
                );
            }
        }

        return $productAbstractCollectionTransfer;
    }
}
