<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;

class ProductPageSearchToPriceProductBridge implements ProductPageSearchToPriceProductInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface
     */
    protected $priceProductFacade;

    /**
     * @param \Spryker\Zed\PriceProduct\Business\PriceProductFacadeInterface $priceProductFacade
     */
    public function __construct($priceProductFacade)
    {
        $this->priceProductFacade = $priceProductFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceFilterTransfer
     *
     * @return int|null
     */
    public function findPriceFor(PriceProductFilterTransfer $priceFilterTransfer)
    {
        return $this->priceProductFacade->findPriceFor($priceFilterTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return array
     */
    public function groupPriceProductCollection(array $priceProductTransfers)
    {
        return $this->priceProductFacade->groupPriceProductCollection($priceProductTransfers);
    }

    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPricesWithoutPriceExtraction(
        int $idProductAbstract,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->priceProductFacade->findProductAbstractPricesWithoutPriceExtraction($idProductAbstract, $priceProductCriteriaTransfer);
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn(array $productAbstractIds): array
    {
        return $this->priceProductFacade->findProductAbstractPricesWithoutPriceExtractionByIdProductAbstractIn($productAbstractIds);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer
     */
    public function buildCriteriaFromFilter(PriceProductFilterTransfer $priceProductFilterTransfer): PriceProductCriteriaTransfer
    {
        return $this->priceProductFacade->buildCriteriaFromFilter($priceProductFilterTransfer);
    }

    /**
     * @param int[] $productAbstractIds
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria(
        array $productAbstractIds,
        ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null
    ): array {
        return $this->priceProductFacade->findProductAbstractPricesWithoutPriceExtractionByProductAbstractIdsAndCriteria($productAbstractIds, $priceProductCriteriaTransfer);
    }
}
