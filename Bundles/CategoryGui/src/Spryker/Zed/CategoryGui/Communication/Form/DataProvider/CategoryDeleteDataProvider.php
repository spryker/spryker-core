<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryGui\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface;

class CategoryDeleteDataProvider
{
    protected const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\CategoryGui\Dependency\QueryContainer\CategoryGuiToCategoryQueryContainerInterface $queryContainer
     */
    public function __construct(CategoryGuiToCategoryQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idCategory
     *
     * @return array
     */
    public function getData($idCategory)
    {
        $categoryEntity = $this->findCategory($idCategory);

        return [
            'id_category_node' => $categoryEntity->getVirtualColumn('id_category_node'),
            'fk_category' => $categoryEntity->getIdCategory(),
        ];
    }

    /**
     * @param int $idCategory
     *
     * @return \Orm\Zed\Category\Persistence\SpyCategory
     */
    protected function findCategory($idCategory)
    {
        return $this->queryContainer
            ->queryCategoryById($idCategory)
            ->innerJoinNode()
            ->useNodeQuery()
                ->filterByIsMain(true)
            ->endUse()
            ->withColumn(SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, 'id_category_node')
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_CATEGORY, 'fk_category')
            ->withColumn(SpyCategoryNodeTableMap::COL_FK_PARENT_CATEGORY_NODE, 'fk_parent_category_node')
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_MAIN, 'is_main')
            ->withColumn(SpyCategoryNodeTableMap::COL_IS_ROOT, 'is_root')
            ->findOne();
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
