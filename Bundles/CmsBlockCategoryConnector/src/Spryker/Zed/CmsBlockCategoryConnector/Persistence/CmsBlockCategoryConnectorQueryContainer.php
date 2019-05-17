<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryAttributeTableMap;
use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
use Orm\Zed\CmsBlockCategoryConnector\Persistence\Map\SpyCmsBlockCategoryConnectorTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockCategoryConnector\Persistence\CmsBlockCategoryConnectorPersistenceFactory getFactory()
 */
class CmsBlockCategoryConnectorQueryContainer extends AbstractQueryContainer implements CmsBlockCategoryConnectorQueryContainerInterface
{
    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnector()
    {
        return $this->getFactory()
            ->createCmsBlockCategoryConnectorQuery();
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
    {
        return $this->queryCmsBlockCategoryConnector()
            ->filterByFkCmsBlock($idCmsBlock)
            ->addJoin(
                [SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY, SpyCmsBlockCategoryConnectorTableMap::COL_FK_CATEGORY_TEMPLATE],
                [SpyCategoryTableMap::COL_ID_CATEGORY, SpyCategoryTableMap::COL_FK_CATEGORY_TEMPLATE],
                Criteria::JOIN
            );
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnectorByIdCategory($idCategory, $idCategoryTemplate)
    {
        return $this->queryCmsBlockCategoryConnector()
            ->filterByFkCategoryTemplate($idCategoryTemplate)
            ->filterByFkCategory($idCategory);
    }

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryWithNamesByIdBlock($idCmsBlock, $idLocale)
    {
        return $this->queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock)
            ->addJoin(
                [
                    SpyCategoryTableMap::COL_ID_CATEGORY,
                    SpyCategoryAttributeTableMap::COL_FK_LOCALE,
                ],
                [
                    SpyCategoryAttributeTableMap::COL_FK_CATEGORY,
                    $idLocale,
                ],
                Criteria::INNER_JOIN
            );
    }

    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryWithBlocksByIdCategoryIdTemplate($idCategory, $idCategoryTemplate)
    {
        return $this->queryCmsBlockCategoryConnectorByIdCategory($idCategory, $idCategoryTemplate)
            ->joinCmsBlock();
    }

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryWithBlocksByIdCategory($idCategory)
    {
        return $this->queryCmsBlockCategoryConnector()
            ->orderByIdCmsBlockCategoryConnector()
            ->filterByFkCategory($idCategory)
            ->joinCmsBlock();
    }

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPositionQuery
     */
    public function queryCmsBlockCategoryPosition()
    {
        return $this->getFactory()
            ->createCmsBlockCategoryPosition();
    }

    /**
     * @api
     *
     * @param string $positionName
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPositionQuery
     */
    public function queryCmsBlockCategoryPositionByName($positionName)
    {
        return $this->queryCmsBlockCategoryPosition()
            ->filterByName($positionName);
    }
}
