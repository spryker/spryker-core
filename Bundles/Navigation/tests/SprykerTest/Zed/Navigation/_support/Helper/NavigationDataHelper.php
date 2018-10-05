<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Navigation\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\NavigationBuilder;
use Generated\Shared\DataBuilder\NavigationNodeBuilder;
use Generated\Shared\DataBuilder\NavigationNodeLocalizedAttributesBuilder;
use Generated\Shared\Transfer\NavigationTransfer;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class NavigationDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    public function haveNavigation(array $seedData = [])
    {
        $navigationTransfer = $this->generateNavigationTransfer($seedData);

        $this->getNavigationFacade()->createNavigation($navigationTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($navigationTransfer) {
            $this->cleanupNavigation($navigationTransfer);
        });

        return $navigationTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function haveNavigationNode(array $seedData = [])
    {
        $navigationNodeTransfer = $this->generateNavigationNodeTransfer($seedData);

        $navigationNodeTransfer = $this->getNavigationFacade()->createNavigationNode($navigationNodeTransfer);

        return $navigationNodeTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    public function haveLocalizedNavigationNode(array $seedData = [])
    {
        $navigationNodeTransfer = $this->generateLocalizedNavigationNodeTransfer($seedData);

        $navigationNodeTransfer = $this->getNavigationFacade()->createNavigationNode($navigationNodeTransfer);

        return $navigationNodeTransfer;
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\NavigationFacadeInterface
     */
    protected function getNavigationFacade()
    {
        return $this->getLocator()->navigation()->facade();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NavigationTransfer
     */
    protected function generateNavigationTransfer(array $seedData = [])
    {
        $navigationTransfer = (new NavigationBuilder($seedData))->build();
        $navigationTransfer->setIdNavigation(null);

        return $navigationTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function generateNavigationNodeTransfer(array $seedData)
    {
        $navigationNodeTransfer = (new NavigationNodeBuilder($seedData))->build();

        return $navigationNodeTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NavigationNodeTransfer
     */
    protected function generateLocalizedNavigationNodeTransfer(array $seedData = [])
    {
        $navigationNodeTransfer = $this->generateNavigationNodeTransfer($seedData);

        $navigationLocalizedAttributes = (new NavigationNodeLocalizedAttributesBuilder($seedData))->build();
        $navigationNodeTransfer->addNavigationNodeLocalizedAttribute($navigationLocalizedAttributes);

        return $navigationNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\NavigationTransfer $navigationTransfer
     *
     * @return void
     */
    protected function cleanupNavigation(NavigationTransfer $navigationTransfer)
    {
        $this->getNavigationFacade()->deleteNavigation($navigationTransfer);
    }
}
