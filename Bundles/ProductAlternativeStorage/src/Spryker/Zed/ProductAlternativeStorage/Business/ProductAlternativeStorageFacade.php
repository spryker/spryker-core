<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductAlternativeStorage\Persistence\ProductAlternativeStorageRepositoryInterface getRepository()
 */
class ProductAlternativeStorageFacade extends AbstractFacade implements ProductAlternativeStorageFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAlternative(array $productIds): void
    {
        $this->getFactory()->createProductAlternativePublisher()->publish($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishAbstractReplacements(array $productIds): void
    {
        $this->getFactory()
            ->createProductReplacementPublisher()
            ->publishAbstractReplacements($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int[] $productIds
     *
     * @return void
     */
    public function publishConcreteReplacements(array $productIds): void
    {
        $this->getFactory()
            ->createProductReplacementPublisher()
            ->publishConcreteReplacements($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\SpyProductAlternativeEntityTransfer[]
     */
    public function getProductAlternativeStorageCollectionByFilter(FilterTransfer $filterTransfer, array $ids): array
    {
        return $this->getRepository()->getProductAlternativeStorageCollectionByFilter($filterTransfer, $ids);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param int[] $productReplacementForStorageIds
     *
     * @return \Generated\Shared\Transfer\SpyProductReplacementForStorageEntityTransfer[]
     */
    public function getProductReplacementForStorageCollectionByFilter(FilterTransfer $filterTransfer, array $productReplacementForStorageIds): array
    {
        return $this->getRepository()->getProductReplacementForStorageCollectionByFilter($filterTransfer, $productReplacementForStorageIds);
    }
}
