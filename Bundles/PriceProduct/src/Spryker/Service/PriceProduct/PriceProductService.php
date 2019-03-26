<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\PriceProduct;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\PriceProduct\PriceProductServiceFactory getFactory()
 */
class PriceProductService extends AbstractService implements PriceProductServiceInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function resolveProductPriceByPriceProductCriteria(array $priceProductTransfers, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?PriceProductTransfer
    {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceValueByPriceProductCriteria($priceProductTransfers, $priceProductCriteriaTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer|null
     */
    public function resolveProductPriceByPriceProductFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): ?PriceProductTransfer
    {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPriceByFilter($priceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function resolveProductPricesByPriceProductFilter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        return $this->getFactory()
            ->createPriceProductMatcher()
            ->matchPricesByFilter($priceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $abstractPriceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $concretePriceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function mergeConcreteAndAbstractPrices(array $abstractPriceProductTransfers, array $concretePriceProductTransfers): array
    {
        return $this->getFactory()
            ->createPriceProductMerger()
            ->mergeConcreteAndAbstractPrices($abstractPriceProductTransfers, $concretePriceProductTransfers);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return string
     */
    public function buildPriceProductGroupKey(PriceProductTransfer $priceProductTransfer): string
    {
        return $this->getFactory()
            ->createPriceProductGroupKeyBuilder()
            ->buildPriceProductGroupKey($priceProductTransfer);
    }
}
