<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Cms\Persistence\Map\SpyCmsPageLocalizedAttributesTableMap;
use Orm\Zed\Url\Persistence\Map\SpyUrlTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;

/**
 * @method \Spryker\Zed\NavigationGui\Persistence\NavigationGuiPersistenceFactory getFactory()
 */
class NavigationGuiQueryContainer extends AbstractQueryContainer implements NavigationGuiQueryContainerInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation()
    {
        return $this->getFactory()->createNavigationQuery();
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCmsPageUrlSuggestions($searchText, $idLocale)
    {
        $searchText = trim($searchText);

        $query = $this->getFactory()
            ->createCmsPageLocalizedAttributesQuery()
            ->addJoin(
                [SpyCmsPageLocalizedAttributesTableMap::COL_FK_CMS_PAGE, SpyCmsPageLocalizedAttributesTableMap::COL_FK_LOCALE],
                [SpyUrlTableMap::COL_FK_RESOURCE_PAGE, SpyUrlTableMap::COL_FK_LOCALE],
                Criteria::RIGHT_JOIN
            )
            ->filterByFkLocale($idLocale)
            ->withColumn(SpyCmsPageLocalizedAttributesTableMap::COL_NAME, 'name')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url')
            ->setFormatter(new PropelArraySetFormatter())
            ->where(
                'LOWER(' . SpyCmsPageLocalizedAttributesTableMap::COL_NAME . ') LIKE ?',
                '%' . mb_strtolower($searchText) . '%'
            );

        return $query;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeUrlSuggestions($searchText, $idLocale)
    {
        $searchText = trim($searchText);

        $query = $this->getFactory()
            ->createCategoryAttributeQuery()
            ->addJoin(
                SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                SpyCategoryNodeTableMap::COL_FK_CATEGORY,
                Criteria::RIGHT_JOIN
            )
            ->addJoin(
                [SpyCategoryNodeTableMap::COL_ID_CATEGORY_NODE, SpyCategoryAttributeTableMap::COL_FK_LOCALE],
                [SpyUrlTableMap::COL_FK_RESOURCE_CATEGORYNODE, SpyUrlTableMap::COL_FK_LOCALE],
                Criteria::RIGHT_JOIN
            )
            ->filterByFkLocale($idLocale)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url')
            ->setFormatter(new PropelArraySetFormatter())
            ->where(
                'LOWER(' . SpyCategoryAttributeTableMap::COL_NAME . ') LIKE ?',
                '%' . mb_strtolower($searchText) . '%'
            );

        return $query;
    }
}
