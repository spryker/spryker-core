<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryNodeTableMap;
use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
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
     * @api
     *
     * @return \Orm\Zed\Navigation\Persistence\SpyNavigationQuery
     */
    public function queryNavigation()
    {
        return $this->getFactory()
            ->getNavigationQueryContainer()
            ->queryNavigation();
    }

    /**
     * @api
     *
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCmsPageUrlSuggestions($searchText, $idLocale)
    {
        /*
         * SELECT scpla.name, su.url
         * FROM spy_cms_page_localized_attributes AS scpla
         *   JOIN spy_url AS su
         *     ON su.fk_resource_page = scpla.fk_cms_page
         *     AND su.fk_locale = scpla.fk_locale
         * WHERE
         *   scpla.fk_locale = :idLocale
         *   AND scpla.name ILIKE '%:searchText%'
         */
        $query = $this->getFactory()
            ->getCmsQueryContainer()
            ->queryCmsPageLocalizedAttributes()
            ->addJoin(
                [SpyCmsPageLocalizedAttributesTableMap::COL_FK_CMS_PAGE, SpyCmsPageLocalizedAttributesTableMap::COL_FK_LOCALE],
                [SpyUrlTableMap::COL_FK_RESOURCE_PAGE, SpyUrlTableMap::COL_FK_LOCALE],
                Criteria::RIGHT_JOIN
            )
            ->filterByFkLocale($idLocale)
            ->filterByName('%' . trim($searchText) . '%', Criteria::ILIKE)
            ->withColumn(SpyCmsPageLocalizedAttributesTableMap::COL_NAME, 'name')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url')
            ->setFormatter(new PropelArraySetFormatter());

        return $query;
    }

    /**
     * @api
     *
     * @param string $searchText
     * @param int $idLocale
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function queryCategoryNodeUrlSuggestions($searchText, $idLocale)
    {
        /*
         * SELECT sca.name, su.url
         * FROM spy_category_attribute AS sca
         *   JOIN spy_category_node AS scn ON scn.fk_category = sca.fk_category
         *   JOIN spy_url su ON
         *     su.fk_resource_categorynode = scn.id_category_node AND
         *     su.fk_locale = sca.fk_locale
         * WHERE
         *   sca.fk_locale = :idLocale
         *   AND sca.name ILIKE '%:searchText%'
         */
        $query = SpyCategoryAttributeQuery::create() // TODO: fix direct usage of SpyCategoryAttributeQuery::create()
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
            ->filterByName('%' . trim($searchText) . '%', Criteria::ILIKE)
            ->withColumn(SpyCategoryAttributeTableMap::COL_NAME, 'name')
            ->withColumn(SpyUrlTableMap::COL_URL, 'url')
            ->setFormatter(new PropelArraySetFormatter());

        return $query;
    }

}
