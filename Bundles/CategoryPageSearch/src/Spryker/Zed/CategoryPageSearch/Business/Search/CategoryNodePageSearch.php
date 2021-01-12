<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryPageSearch\Business\Search;

use Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface;
use Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface;

class CategoryNodePageSearch implements CategoryNodePageSearchInterface
{
    /**
     * @var \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface
     */
    protected $categoryNodePageSearchWriter;

    /**
     * @param \Spryker\Zed\CategoryPageSearch\Persistence\CategoryPageSearchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\CategoryPageSearch\Business\Writer\CategoryNodePageSearchWriterInterface $categoryNodePageSearchWriter
     */
    public function __construct(
        CategoryPageSearchQueryContainerInterface $queryContainer,
        CategoryNodePageSearchWriterInterface $categoryNodePageSearchWriter
    ) {
        $this->queryContainer = $queryContainer;
        $this->categoryNodePageSearchWriter = $categoryNodePageSearchWriter;
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function publish(array $categoryNodeIds): void
    {
        $this->categoryNodePageSearchWriter->writeCollection($categoryNodeIds);
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return void
     */
    public function unpublish(array $categoryNodeIds): void
    {
        $spyCategoryNodePageSearchEntities = $this->findCategoryNodePageSearchEntitiesByCategoryNodeIds($categoryNodeIds);

        $this->deleteSearchData($spyCategoryNodePageSearchEntities);
    }

    /**
     * @param \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[] $spyCategoryNodePageSearchEntities
     *
     * @return void
     */
    protected function deleteSearchData(array $spyCategoryNodePageSearchEntities): void
    {
        foreach ($spyCategoryNodePageSearchEntities as $spyCategoryNodePageSearchEntity) {
            $spyCategoryNodePageSearchEntity->delete();
        }
    }

    /**
     * @param int[] $categoryNodeIds
     *
     * @return \Orm\Zed\CategoryPageSearch\Persistence\SpyCategoryNodePageSearch[]
     */
    protected function findCategoryNodePageSearchEntitiesByCategoryNodeIds(array $categoryNodeIds): array
    {
        return $this->queryContainer->queryCategoryNodePageSearchByIds($categoryNodeIds)->find()->getArrayCopy();
    }
}
