<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Spryker\Shared\ProductLabelSearch\ProductLabelSearchConfig;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductLabel\Persistence\Propel\SpyProductLabelProductAbstractQuery;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\ProductLabelSearch\Communication\ProductLabelSearchCommunicationFactory getFactory()
 */
class ProductLabelSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
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
        $productLabelIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventTransfers);
        //TODO move this to QueryContainer
        $productAbstractIds = SpyProductLabelProductAbstractQuery::create()
            ->filterByFkProductLabel_In($productLabelIds)
            ->withColumn(SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT, 'fkProductAbstract')
            ->select(['fkProductAbstract'])
            ->find()
            ->getData()
        ;

        $this->getFactory()->getProductPageSearchFacade()->refresh($productAbstractIds, [ProductLabelSearchConfig::PLUGIN_PRODUCT_LABEL_DATA]);
    }

}
