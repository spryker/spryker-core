<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsNavigationConnector\Business\Writer\Navigation;

use Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReaderInterface;
use Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface;

class NavigationNodeWriter implements NavigationNodeWriterInterface
{
    /**
     * @var \Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReaderInterface
     */
    protected $navigationNodeReader;

    /**
     * @param \Spryker\Zed\CmsNavigationConnector\Dependency\Facade\CmsNavigationConnectorToNavigationFacadeInterface $navigationFacade
     * @param \Spryker\Zed\CmsNavigationConnector\Business\Model\NavigationNodeReaderInterface $navigationNodeReader
     */
    public function __construct(
        CmsNavigationConnectorToNavigationFacadeInterface $navigationFacade,
        NavigationNodeReaderInterface $navigationNodeReader
    ) {
        $this->navigationFacade = $navigationFacade;
        $this->navigationNodeReader = $navigationNodeReader;
    }

    /**
     * @param int $idCmsPage
     *
     * @return void
     */
    public function deleteNavigationNodesByIdCmsPage(int $idCmsPage): void
    {
        $navigationNodes = $this->navigationNodeReader->getNavigationNodesFromCmsPageId($idCmsPage);

        foreach ($navigationNodes as $navigationNode) {
            $this->navigationFacade->deleteNavigationNode($navigationNode);
        }
    }
}
