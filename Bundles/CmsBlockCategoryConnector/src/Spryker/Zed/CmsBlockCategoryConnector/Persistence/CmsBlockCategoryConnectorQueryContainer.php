<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Persistence;

use Orm\Zed\Category\Persistence\Map\SpyCategoryTableMap;
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
            ->filterByFkCmsBlock($idCmsBlock);
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
            ->useCategoryQuery()
                ->useAttributeQuery()
                    ->filterByFkLocale($idLocale)
                ->endUse()
            ->endUse();
    }

}
