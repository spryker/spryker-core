<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Generated\Shared\Transfer\CurrentProductConcretePriceTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuickOrder\QuickOrderFactory getFactory()
 */
class QuickOrderClient extends AbstractClient implements QuickOrderClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->getFactory()
            ->createProductConcreteExpander()
            ->expandProductConcreteTransfer($productConcreteTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductConcretePriceTransfer
     */
    public function getProductConcreteSumPrice(CurrentProductConcretePriceTransfer $currentProductConcretePriceTransfer): CurrentProductConcretePriceTransfer
    {
        return $this->getFactory()
            ->createProductConcretePriceReader()
            ->getProductConcreteSumPrice($currentProductConcretePriceTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateQuantityRestrictions(QuickOrderItemTransfer $quickOrderItemTransfer): ProductQuantityValidationResponseTransfer
    {
        return $this->getFactory()
            ->createProductQuantityRestrictionsValidator()
            ->validateQuantityRestrictions($quickOrderItemTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    public function findProductConcretesByQuickOrderItemTransfers(array $quickOrderItemTransfers): array
    {
        return $this->getFactory()
            ->createProductConcreteReader()
            ->findProductConcretesByQuickOrderItemTransfers($quickOrderItemTransfers);
    }
}
