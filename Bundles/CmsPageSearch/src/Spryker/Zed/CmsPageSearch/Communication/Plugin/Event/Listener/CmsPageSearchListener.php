<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsPageSearch\Communication\Plugin\Event\Listener;

use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\Event\Dependency\Plugin\EventBulkHandlerInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

/**
 * @method \Spryker\Zed\CmsPageSearch\Communication\CmsPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\CmsPageSearch\Persistence\CmsPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CmsPageSearch\Business\CmsPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\CmsPageSearch\CmsPageSearchConfig getConfig()
 */
class CmsPageSearchListener extends AbstractPlugin implements EventBulkHandlerInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @param array $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName)
    {
        $this->preventTransaction();
        $cmsPageIds = $this->getFactory()->getEventBehaviorFacade()->getEventTransferIds($eventEntityTransfers);

        if (
            $eventName === CmsEvents::ENTITY_SPY_CMS_PAGE_UPDATE ||
            $eventName === CmsEvents::CMS_VERSION_PUBLISH
        ) {
            $this->getFacade()->publish($cmsPageIds);
        } elseif (
            $eventName === CmsEvents::ENTITY_SPY_CMS_PAGE_DELETE ||
            $eventName === CmsEvents::CMS_VERSION_UNPUBLISH
        ) {
            $this->getFacade()->unpublish($cmsPageIds);
        }
    }
}
