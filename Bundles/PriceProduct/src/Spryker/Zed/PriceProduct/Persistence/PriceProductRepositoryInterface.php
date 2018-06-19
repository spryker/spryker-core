<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Persistence;

use Propel\Runtime\Collection\ObjectCollection;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer;

interface PriceProductRepositoryInterface
{
    /**
     * @api
     *
     * @param string $concreteSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductConcretePricesBySkuAndCriteria(
        $concreteSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    );

    /**
     * @api
     *
     * @param string $abstractSku
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductAbstractPricesBySkuAndCriteria(
        $abstractSku,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    );

    /**
     * @api
     *
     * @param int $idProductConcrete
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductConcretePricesByIdAndCriteria(
        $idProductConcrete,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    );

    /**
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Orm\Zed\PriceProduct\Persistence\SpyPriceProductStore[]
     */
    public function findProductAbstractPricesByIdAndCriteria(
        $idProductAbstract,
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    );

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildDefaultPriceDimensionCriteria(
        PriceProductCriteriaTransfer $priceProductCriteriaTransfer
    ): ?QueryCriteriaTransfer;

    /**
     * @param int $idPriceProductStore
     *
     * @return \Generated\Shared\Transfer\SpyPriceProductDefaultEntityTransfer|null
     */
    public function findPriceProductDefaultByIdPriceProductStore(int $idPriceProductStore): ?SpyPriceProductDefaultEntityTransfer;
}
