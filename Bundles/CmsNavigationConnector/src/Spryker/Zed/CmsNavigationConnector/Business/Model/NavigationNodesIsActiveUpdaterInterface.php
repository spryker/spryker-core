<?php


namespace Spryker\Zed\CmsNavigationConnector\Business\Model;

interface NavigationNodesIsActiveUpdaterInterface
{
    /**
     * @param int $idCmsPage
     * @param bool $isActive
     */
    public function updateCmsPageNavigationNodes($idCmsPage, $isActive);
}