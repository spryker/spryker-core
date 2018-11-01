<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryNavigationConnector\Dependency\Facade;

use Generated\Shared\Transfer\NavigationNodeTransfer;

class CategoryNavigationConnectorToNavigationFacadeBridge implements CategoryNavigationConnectorToNavigationFacadeInterface
{
    /**
     * @var \Spryker\Zed\Navigation\Business\NavigationFacadeInterface
     */
    protected $navigationFacade;

    /**
     * @param \Spryker\Zed\Navigation\Business\NavigationFacadeInterface $navigationFacade
     */
    public function __construct($navigationFacade)
    {
        $this->navigationFacade = $navigationFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationNodeTransfer $navigationNodeTransfer
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function updateNavigationNode(NavigationNodeTransfer $navigationNodeTransfer)
    {
        return $this->navigationFacade->updateNavigationNode($navigationNodeTransfer);
    }
}
