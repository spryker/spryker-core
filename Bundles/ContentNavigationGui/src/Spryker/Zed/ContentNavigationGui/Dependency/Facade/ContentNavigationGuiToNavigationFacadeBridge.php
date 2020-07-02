<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentNavigationGui\Dependency\Facade;

class ContentNavigationGuiToNavigationFacadeBridge implements ContentNavigationGuiToNavigationFacadeInterface
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
     * @return \Generated\Shared\Transfer\NavigationTransfer[]
     */
    public function getAllNavigations(): array
    {
        return $this->navigationFacade->getAllNavigations();
    }
}
