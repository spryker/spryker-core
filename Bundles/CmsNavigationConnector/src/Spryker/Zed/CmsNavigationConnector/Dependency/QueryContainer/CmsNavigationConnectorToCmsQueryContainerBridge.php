<?php

namespace Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer;

class CmsNavigationConnectorToCmsQueryContainerBridge implements CmsNavigationConnectorToCmsQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @param \Spryker\Zed\Cms\Persistence\CmsQueryContainerInterface $cmsQueryContainer
     */
    public function __construct($cmsQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrlQuery
     */
    public function queryResourceUrlByCmsPageId($idCmsPage)
    {
        return $this->cmsQueryContainer->queryResourceUrlByCmsPageId($idCmsPage);
    }
}
