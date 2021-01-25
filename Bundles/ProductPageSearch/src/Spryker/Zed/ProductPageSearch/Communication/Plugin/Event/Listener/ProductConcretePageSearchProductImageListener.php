<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event\Listener;

use Generated\Shared\Transfer\ProductImageFilterTransfer;

/**
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchProductImageListener extends AbstractProductConcretePageSearchListener
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $productImageIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferIds($eventEntityTransfers);

        $productConcreteIds = $this->getFactory()
            ->getProductImageFacade()
            ->getProductConcreteIds((new ProductImageFilterTransfer())->setProductImageIds($productImageIds));

        $this->publish($productConcreteIds);
    }
}
