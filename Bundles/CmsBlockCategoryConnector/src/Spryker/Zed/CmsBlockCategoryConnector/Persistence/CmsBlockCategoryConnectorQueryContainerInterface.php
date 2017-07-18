<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;

interface CmsBlockCategoryConnectorQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnector();

    /**
     * @api
     *
     * @param int $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnectorByIdCmsBlock($idCmsBlock);

    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryConnectorByIdCategory($idCategory, $idCategoryTemplate);

    /**
     * @api
     *
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryWithNamesByIdBlock($idCmsBlock, $idLocale);

    /**
     * @api
     *
     * @param int $idCategory
     * @param int $idCategoryTemplate
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryWithBlocksByIdCategoryIdTemplate($idCategory, $idCategoryTemplate);

    /**
     * @api
     *
     * @param int $idCategory
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryConnectorQuery
     */
    public function queryCmsBlockCategoryWithBlocksByIdCategory($idCategory);

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPositionQuery
     */
    public function queryCmsBlockCategoryPosition();

    /**
     * @api
     *
     * @param string $positionName
     *
     * @return \Orm\Zed\CmsBlockCategoryConnector\Persistence\SpyCmsBlockCategoryPositionQuery
     */
    public function queryCmsBlockCategoryPositionByName($positionName);

}
