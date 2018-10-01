<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Communication\Form\DataProvider;

use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface;
use Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface;

class CategoryDeleteDataProvider
{
    public const DATA_CLASS = 'data_class';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Category\Dependency\Facade\CategoryToLocaleInterface $localeFacade
     */
    public function __construct(
        CategoryQueryContainerInterface $queryContainer,
        CategoryToLocaleInterface $localeFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->localeFacade = $localeFacade;
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
        $categoryEntity = $this->queryContainer
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

        return $categoryEntity;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
