<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrder;

use Generated\Shared\Transfer\QuickOrderTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\QuickOrder\QuickOrderFactory getFactory()
 */
class QuickOrderClient extends AbstractClient implements QuickOrderClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getProductsByQuickOrder(QuickOrderTransfer $quickOrderTransfer): array
    {
        return $this->getFactory()
            ->createProductConcreteResolver()
            ->getProductsByQuickOrder($quickOrderTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer[] $productConcreteTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function expandProductConcreteTransfers(array $productConcreteTransfers): array
    {
        return $this->getFactory()
            ->createProductConcreteExpander()
            ->expand($productConcreteTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function buildQuickOrderTransfer(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer
    {
        return $this->getFactory()
            ->createQuickOrderTransferBuilder()
            ->build($quickOrderTransfer);
    }
}
