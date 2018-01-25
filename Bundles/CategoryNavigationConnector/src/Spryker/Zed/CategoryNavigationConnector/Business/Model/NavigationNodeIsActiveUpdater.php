<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Business\Model;

use Spryker\Zed\CategoryNavigationConnector\Dependency\Facade\CategoryNavigationConnectorToNavigationFacadeInterface;

class NavigationNodeIsActiveUpdater implements NavigationNodeIsActiveUpdaterInterface
{
    /**
     * @var \Spryker\Zed\CategoryNavigationConnector\Dependency\Facade\CategoryNavigationConnectorToNavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @var \Spryker\Zed\CategoryNavigationConnector\Business\Model\NavigationNodeReaderInterface
     */
    protected $navigationNodeReader;

    /**
     * @param \Spryker\Zed\CategoryNavigationConnector\Dependency\Facade\CategoryNavigationConnectorToNavigationFacadeInterface $navigationFacade
     * @param \Spryker\Zed\CategoryNavigationConnector\Business\Model\NavigationNodeReaderInterface $navigationNodeReader
     */
    public function __construct(CategoryNavigationConnectorToNavigationFacadeInterface $navigationFacade, NavigationNodeReaderInterface $navigationNodeReader)
    {
        $this->navigationFacade = $navigationFacade;
        $this->navigationNodeReader = $navigationNodeReader;
    }

    /**
     * @param int $idCategoryNode
     * @param bool $isActive
     *
     * @return void
     */
    public function updateCategoryNodeNavigationNodes($idCategoryNode, $isActive)
    {
        $navigationNodes = $this->navigationNodeReader->getNavigationNodesFromCategoryNodeId($idCategoryNode);
        foreach ($navigationNodes as $navigationNodeTransfer) {
            $navigationNodeTransfer->setIsActive($isActive);
            $this->navigationFacade->updateNavigationNode($navigationNodeTransfer);
        }
    }
}
