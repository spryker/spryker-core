<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener;

use Orm\Zed\Cms\Persistence\Map\SpyCmsPageStoreTableMap;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsPageSearch\Communication\CmsPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface getFacade()
 */
class CmsPageStoreSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * {@inheritdoc}
     *  - Saves new store relation on cms page store table changes.
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

        $cmsPageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferForeignKeys(
            $eventTransfers,
            SpyCmsPageStoreTableMap::COL_FK_CMS_PAGE
        );

        $this->getFacade()->publish($cmsPageIds);
    }
}
