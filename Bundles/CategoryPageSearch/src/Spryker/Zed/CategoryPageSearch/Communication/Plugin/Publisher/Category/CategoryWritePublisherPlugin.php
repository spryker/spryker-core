<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Communication\Plugin\Publisher\Category;

use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherPluginInterface;

/**
 * @method \Spryker\Zed\CategoryPageSearch\Business\CategoryPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryPageSearch\Communication\CategoryPageSearchCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryPageSearch\CategoryPageSearchConfig getConfig()
 */
class CategoryWritePublisherPlugin extends AbstractPlugin implements PublisherPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param array $eventEntityTransfers
     * @param string $eventName
     *
     * @return void
     */
    public function handleBulk(array $eventEntityTransfers, $eventName): void
    {
        $this->getFacade()->writeCategoryNodePageSearchCollectionByCategoryEvents($eventEntityTransfers);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return array
     */
    public function getSubscribedEvents(): array
    {
        return [
            CategoryPageSearchConstants::CATEGORY_STORE_PUBLISH,
            CategoryPageSearchConstants::CATEGORY_STORE_UNPUBLISH,
            CategoryPageSearchConstants::ENTITY_SPY_CATEGORY_STORE_CREATE,
            CategoryPageSearchConstants::ENTITY_SPY_CATEGORY_STORE_UPDATE,
            CategoryPageSearchConstants::ENTITY_SPY_CATEGORY_STORE_DELETE,
        ];
    }
}
