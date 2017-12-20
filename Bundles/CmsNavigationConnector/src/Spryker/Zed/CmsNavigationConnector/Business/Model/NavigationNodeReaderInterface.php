<?php


namespace Spryker\Zed\CmsNavigationConnector\Business\Model;


interface NavigationNodeReaderInterface
{
    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function getNavigationNodesFromCmsPageId($idCmsPage);
}