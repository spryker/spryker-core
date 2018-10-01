<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearchConfigStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductSearch\Persistence\SpyProductSearchAttributeQuery;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductSearch\Dependency\ProductSearchEvents;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductSearchConfigStorage\Persistence\ProductSearchConfigStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ProductSearchConfigStorage\Communication\ProductSearchConfigStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductSearchConfigStorage\Business\ProductSearchConfigStorageFacadeInterface getFacade()
 */
class ProductSearchConfigStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventTransfers, $eventName)
    {
        $this->preventTransaction();
        $productSearchAttributesCount = SpyProductSearchAttributeQuery::create()->count();

        if (($eventName === ProductSearchEvents::ENTITY_SPY_PRODUCT_SEARCH_ATTRIBUTE_DELETE || $eventName === ProductSearchEvents::PRODUCT_SEARCH_CONFIG_UNPUBLISH)
            && $productSearchAttributesCount === 0
        ) {
            $this->getFacade()->unpublish();

            return;
        }

        $this->getFacade()->publish();
    }
}
