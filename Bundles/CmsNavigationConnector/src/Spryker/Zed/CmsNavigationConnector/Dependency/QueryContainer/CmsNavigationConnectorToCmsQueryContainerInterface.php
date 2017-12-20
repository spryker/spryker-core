<?php


namespace Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer;


interface CmsNavigationConnectorToCmsQueryContainerInterface
{
    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCmsPageId($idCmsPage);
}