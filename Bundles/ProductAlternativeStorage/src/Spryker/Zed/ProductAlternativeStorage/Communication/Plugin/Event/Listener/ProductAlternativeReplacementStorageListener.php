<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\ProductAlternative\Persistence\Map\SpyProductAlternativeTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\ProductAlternativeStorage\Business\ProductAlternativeStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductAlternativeStorage\Communication\ProductAlternativeStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductAlternativeStorage\ProductAlternativeStorageConfig getConfig()
 */
class ProductAlternativeReplacementStorageListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    /**
     * @api
     *
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $eventBehaviorFacade = $this->getFactory()->getEventBehaviorFacade();

        $productAbstractIds = $eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, SpyProductAlternativeTableMap::COL_FK_PRODUCT_ABSTRACT_ALTERNATIVE);

        if ($productAbstractIds) {
            $this->getFacade()->publishAbstractReplacements($productAbstractIds);
        }

        $productConcreteIds = $eventBehaviorFacade->getEventTransferForeignKeys($eventEntityTransfers, SpyProductAlternativeTableMap::COL_FK_PRODUCT_CONCRETE_ALTERNATIVE);

        if ($productConcreteIds) {
            $this->getFacade()->publishConcreteReplacements($productConcreteIds);
        }
    }
}
