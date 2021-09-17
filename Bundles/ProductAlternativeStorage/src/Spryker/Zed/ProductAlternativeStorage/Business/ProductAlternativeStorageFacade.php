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
     * @param array<int> $productIds
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
     * @param array<int> $productIds
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
     * @param array<int> $productIds
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
     * @param array<int> $productAlternativeStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds(
        FilterTransfer $filterTransfer,
        array $productAlternativeStorageIds = []
    ): array {
        return $this->getRepository()
            ->getSynchronizationDataTransfersByFilterAndProductAlternativeStorageIds($filterTransfer, $productAlternativeStorageIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param array<int> $productReplacementForStorageIds
     *
     * @return array<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds(
        FilterTransfer $filterTransfer,
        array $productReplacementForStorageIds = []
    ): array {
        return $this->getRepository()
            ->getSynchronizationDataTransfersByFilterAndProductReplacementForStorageIds($filterTransfer, $productReplacementForStorageIds);
    }
}
