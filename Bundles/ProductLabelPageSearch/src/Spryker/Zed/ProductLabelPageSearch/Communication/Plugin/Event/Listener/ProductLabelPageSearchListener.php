<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Shared\ProductLabelPageSearch\ProductLabelPageSearchConfig;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductLabelPageSearch\Communication\ProductLabelPageSearchCommunicationFactory getFactory()
 */
class ProductLabelPageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productAbstractIds = $this->getFactory()
            ->getEventBehaviorFacade()
            ->getEventTransferForeignKeys($eventTransfers, SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT);

        $this->getFactory()->getProductPageSearchFacade()->refresh($productAbstractIds, [ProductLabelPageSearchConfig::PLUGIN_PRODUCT_LABEL_PAGE_DATA]);
    }

}
