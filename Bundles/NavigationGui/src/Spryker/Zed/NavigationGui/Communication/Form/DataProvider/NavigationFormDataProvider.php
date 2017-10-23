<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\NavigationGui\Communication\Form\DataProvider;

use Generated\Shared\Transfer\NavigationTransfer;
use Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface;

class NavigationFormDataProvider
{
    /**
     * @var \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface
     */
    protected $navigationFacade;

    /**
     * @param \Spryker\Zed\NavigationGui\Dependency\Facade\NavigationGuiToNavigationInterface $navigationFacade
     */
    public function __construct(NavigationGuiToNavigationInterface $navigationFacade)
    {
        $this->navigationFacade = $navigationFacade;
    }

    /**
     * @param int|null $idNavigation
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer|null
     */
    public function getData($idNavigation = null)
    {
        $navigationTransfer = new NavigationTransfer();
        $navigationTransfer->setIdNavigation($idNavigation);

        return $this->navigationFacade->findNavigation($navigationTransfer);
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }
}
