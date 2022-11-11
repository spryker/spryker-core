<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Shared\CategoryStorage\CategoryStorageConfig;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\CategoryStorage\Business\CategoryStorageFacadeInterface getFacade()
 * @method \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CategoryStorage\Communication\CategoryStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryStorage\CategoryStorageConfig getConfig()
 */
class CategoryTreePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns an array with `NodeTransfer` with not empty id column in case `$offset === 0` to trigger event, else - returns an empty array.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Generated\Shared\Transfer\NodeTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        return $offset === 0 ? [(new NodeTransfer())->setIdCategoryNode(0)] : [];
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return CategoryStorageConstants::CATEGORY_TREE_RESOURCE_NAME;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return CategoryStorageConfig::CATEGORY_TREE_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getIdColumnName(): string
    {
        return SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE;
    }
}
