<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductReviewStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductReviewStorage\Persistence\ProductReviewStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductReviewStorage\Communication\ProductReviewStorageCommunicationFactory getFactory()
 */
class ProductReviewPublishStorageListener extends AbstractProductReviewStorageListener
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $productAbstractIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);

        $this->publish($productAbstractIds);
    }
}
