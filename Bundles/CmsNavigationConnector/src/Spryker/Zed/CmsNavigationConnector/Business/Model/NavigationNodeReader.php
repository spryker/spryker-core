<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Business\Model;

use Generated\Shared\Transfer\NavigationNodeTransfer;
use Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToCmsQueryContainerInterface;
use Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToNavigationQueryContainerInterface;

class NavigationNodeReader implements NavigationNodeReaderInterface
{
    /**
     * @var \Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToCmsQueryContainerInterface
     */
    protected $cmsQueryContainer;

    /**
     * @var \Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToNavigationQueryContainerInterface
     */
    protected $navigationQueryContainer;

    /**
     * @param \Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToCmsQueryContainerInterface $cmsQueryContainer
     * @param \Spryker\Zed\CmsNavigationConnector\Dependency\QueryContainer\CmsNavigationConnectorToNavigationQueryContainerInterface $navigationQueryContainer
     */
    public function __construct(CmsNavigationConnectorToCmsQueryContainerInterface $cmsQueryContainer, CmsNavigationConnectorToNavigationQueryContainerInterface $navigationQueryContainer)
    {
        $this->cmsQueryContainer = $cmsQueryContainer;
        $this->navigationQueryContainer = $navigationQueryContainer;
    }

    /**
     * @param int $idCmsPage
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer[]
     */
    public function getNavigationNodesFromCmsPageId($idCmsPage)
    {
        $navigationNodes = [];
        $urlEntities = $this->cmsQueryContainer->queryResourceUrlByCmsPageId($idCmsPage)->find();
        foreach ($urlEntities as $url) {
            $navigationNodeEntities = $this->navigationQueryContainer->queryNavigationNodeByFkUrl($url->getIdUrl())->find();
            foreach ($navigationNodeEntities as $navigationNode) {
                $navigationNodes[] = (new NavigationNodeTransfer())->fromArray($navigationNode->toArray(), true);
            }
        }

        return $navigationNodes;
    }
}
