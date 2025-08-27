<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\CmsPageMessageBrokerRequestTransfer;
use Spryker\Zed\Cms\Dependency\CmsEvents;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\Cms\Business\CmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Cms\CmsConfig getConfig()
 * @method \Spryker\Zed\Cms\Communication\CmsCommunicationFactory getFactory()
 * @method \Spryker\Zed\Cms\Business\CmsBusinessFactory getBusinessFactory()
 */
class CmsPageVersionPublishedMessageBrokerPublisherPlugin extends AbstractCmsPageMessageBrokerPublisherPlugin implements PublisherPluginInterface
{
    /**
     * @param array<\Generated\Shared\Transfer\EventEntityTransfer> $eventEntityTransfers
     *
     * @return void
     */
    protected function processEventChunk(array $eventEntityTransfers): void
    {
        $cmsVersionIds = $this->getIds($eventEntityTransfers);

        $cmsPageMessageBrokerRequestTransfer = new CmsPageMessageBrokerRequestTransfer();
        $cmsPageMessageBrokerRequestTransfer->setCmsVersionIds($cmsVersionIds);

        $this->getBusinessFactory()->createCmsPageMessageBrokerPublisher()->sendCmsPagesToMessageBroker($cmsPageMessageBrokerRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array<string>
     */
    public function getSubscribedEvents(): array
    {
        return [
            CmsEvents::CMS_VERSION_PUBLISH,
            CmsEvents::ENTITY_SPY_CMS_VERSION_CREATE,
        ];
    }
}
