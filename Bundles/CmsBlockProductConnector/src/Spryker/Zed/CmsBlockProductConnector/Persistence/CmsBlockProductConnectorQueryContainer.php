<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\CmsBlockProductConnector\Persistence\CmsBlockProductConnectorPersistenceFactory getFactory()
 */
class CmsBlockProductConnectorQueryContainer extends AbstractQueryContainer implements CmsBlockProductConnectorQueryContainerInterface
{

    /**
     * @api
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockCategoryConnector()
    {
        return $this->getFactory()
            ->createCmsBlockProductConnectorQuery();
    }

    /**
     * @api
     *
     * @param $idCmsBlock
     *
     * @return \Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock)
    {
        return $this->queryCmsBlockCategoryConnector()
            ->filterByFkCmsBlock($idCmsBlock);
    }

}
