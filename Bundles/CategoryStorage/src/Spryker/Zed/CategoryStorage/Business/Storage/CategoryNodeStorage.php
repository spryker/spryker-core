<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryStorage\Business\Storage;

use Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface;
use Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface;

class CategoryNodeStorage implements CategoryNodeStorageInterface
{
    /**
     * @var \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface
     */
    protected $categoryNodeStorageWriter;

    /**
     * @param \Spryker\Zed\CategoryStorage\Persistence\CategoryStorageQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryStorage\Business\Writer\CategoryNodeStorageWriterInterface $categoryNodeStorageWriter
     */
    public function __construct(
        CategoryStorageQueryContainerInterface $queryContainer,
        CategoryNodeStorageWriterInterface $categoryNodeStorageWriter
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryNodeStorageWriter = $categoryNodeStorageWriter;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds): void
    {
        $this->categoryNodeStorageWriter->writeCollection($categoryNodeIds);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds): void
    {
        $this->deleteCollection($categoryNodeIds);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    protected function deleteCollection(array $categoryNodeIds): void
    {
        $categoryNodeStorageEntities = $this->findCategoryNodeStorageEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->deleteStorageData($categoryNodeStorageEntities);
    }

    /**
     * @param \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[] $categoryNodeStorageEntities
     *
     * @return void
     */
    protected function deleteStorageData(array $categoryNodeStorageEntities): void
    {
        foreach ($categoryNodeStorageEntities as $categoryNodeStorageEntity) {
            $categoryNodeStorageEntity->delete();
        }
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryStorage\Persistence\SpyCategoryNodeStorage[]
     */
    protected function findCategoryNodeStorageEntitiesByCategoryNodeIds(array $categoryNodeIds): array
    {
        return $this->queryContainer
            ->queryCategoryNodeStorageByIds($categoryNodeIds)
            ->find()
            ->getArrayCopy();
    }
}
