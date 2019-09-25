<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxStorage\Communication\Plugin\Event\Listener;

use Orm\Zed\Tax\Persistence\Map\SpyTaxSetTaxTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\TaxStorage\TaxStorageConfig getConfig()
 * @method \Spryker\Zed\TaxStorage\Business\TaxStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\TaxStorage\Communication\TaxStorageCommunicationFactory getFactory()
 */
class TaxSetTaxStoragePublishListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritDoc}
     *
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
        $taxSetIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys($eventTransfers, SpyTaxSetTaxTableMap::COL_FK_TAX_SET);

        $this->getFacade()->publishByTaxSetIds($taxSetIds);
    }
}
