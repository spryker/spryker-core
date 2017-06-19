<?php

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

use Orm\Zed\CmsBlockProductConnector\Persistence\SpyCmsBlockProductConnectorQuery;

interface CmsBlockProductConnectorQueryContainerInterface
{
    /**
     * @return SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockCategoryConnector();

    /**
     * @param $idCmsBlock
     *
     * @return SpyCmsBlockProductConnectorQuery
     */
    public function queryCmsBlockProductConnectorByIdCmsBlock($idCmsBlock);

}