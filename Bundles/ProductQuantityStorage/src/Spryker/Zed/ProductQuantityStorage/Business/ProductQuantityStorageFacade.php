<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductQuantityStorage\Business;

use Generated\Shared\Transfer\FilterTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductQuantityStorage\Business\ProductQuantityStorageBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\ProductQuantityStorage\Persistence\ProductQuantityStorageRepositoryInterface getRepository()
 */
class ProductQuantityStorageFacade extends AbstractFacade implements ProductQuantityStorageFacadeInterface
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
    public function publishProductQuantity(array $productIds): void
    {
        $this->getFactory()->createProductQuantityStorageWriter()->publish($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer>
     */
    public function findProductQuantityTransfers(): array
    {
        return $this->getFactory()->getProductQuantityFacade()->findProductQuantityTransfers();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array<int> $productIds
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer>
     */
    public function findProductQuantityByProductIdsTransfers(array $productIds): array
    {
        return $this->getFactory()->getProductQuantityFacade()->findProductQuantityTransfersByProductIds($productIds);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductQuantityTransfer|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer>
     */
    public function findFilteredProductQuantityTransfers(FilterTransfer $filterTransfer): array
    {
        return $this->getFactory()->getProductQuantityFacade()->findFilteredProductQuantityTransfers($filterTransfer);
    }
}
