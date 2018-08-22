<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCategoryResourceAliasStorage\Communication\Plugin\Event\Listener;

use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Business\ProductCategoryResourceAliasStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductCategoryResourceAliasStorage\Communication\ProductCategoryResourceAliasStorageCommunicationFactory getFactory()
 */
class ProductAbstractCategoryMappingResourceStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName): void
    {
        $productAbstractCategorysIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        $this->getFacade()->updateProductAbstractCategoryStorageSkus($productAbstractCategorysIds);
    }
}
