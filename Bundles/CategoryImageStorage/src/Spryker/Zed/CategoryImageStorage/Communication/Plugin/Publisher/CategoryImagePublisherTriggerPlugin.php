<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImageStorage\Communication\Plugin\Publisher;

use Generated\Shared\Transfer\CategoryCriteriaTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Spryker\Shared\CategoryImageStorage\CategoryImageStorageConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PublisherExtension\Dependency\Plugin\PublisherTriggerPluginInterface;

/**
 * @method \Spryker\Zed\CategoryImageStorage\CategoryImageStorageConfig getConfig()
 * @method \Spryker\Zed\CategoryImageStorage\Communication\CategoryImageStorageCommunicationFactory getFactory()
 * @method \Spryker\Zed\CategoryImageStorage\Business\CategoryImageStorageFacadeInterface getFacade()
 */
class CategoryImagePublisherTriggerPlugin extends AbstractPlugin implements PublisherTriggerPluginInterface
{
    /**
     * @uses \Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap::COL_ID_CATEGORY;
     *
     * @var string
     */
    protected const COL_ID_CATEGORY = 'spy_category.id_category';

    /**
     * {@inheritDoc}
     *  - Retrieves collection of categories by offset and limit from Persistence.
     *
     * @api
     *
     * @param int $offset
     * @param int $limit
     *
     * @return array<\Spryker\Shared\Kernel\Transfer\AbstractTransfer>
     */
    public function getData(int $offset, int $limit): array
    {
        $categoryCriteriaTransfer = $this->createCategoryCriteriaTransfer($offset, $limit);

        return $this->getFactory()->getCategoryFacade()
            ->getCategoryCollection($categoryCriteriaTransfer)->getCategories()->getArrayCopy();
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
        return CategoryImageStorageConfig::CATEGORY_IMAGE_RESOURCE_NAME;
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
        return CategoryImageStorageConfig::CATEGORY_IMAGE_CATEGORY_PUBLISH;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return static::COL_ID_CATEGORY;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Generated\Shared\Transfer\CategoryCriteriaTransfer
     */
    protected function createCategoryCriteriaTransfer(int $offset, int $limit): CategoryCriteriaTransfer
    {
        $paginationTransfer = (new PaginationTransfer())->setOffset($offset)->setLimit($limit);

        return (new CategoryCriteriaTransfer())->setPagination($paginationTransfer);
    }
}
