<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher\Category;

use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Publishes category node data by CategoryStore publish and un-publish events.
     * - Publishes category node data by SpyCategoryStore entity events.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\EventEntityTransfer[] $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCategoryNodeStorageCollectionByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            CategoryStorageConstants::CATEGORY_STORE_PUBLISH,
            CategoryStorageConstants::CATEGORY_STORE_UNPUBLISH,
            CategoryStorageConstants::ENTITY_SPY_CATEGORY_STORE_CREATE,
            CategoryStorageConstants::ENTITY_SPY_CATEGORY_STORE_UPDATE,
            CategoryStorageConstants::ENTITY_SPY_CATEGORY_STORE_DELETE,
        ];
    }
}
