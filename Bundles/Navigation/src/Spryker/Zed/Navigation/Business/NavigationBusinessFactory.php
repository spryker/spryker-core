<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Navigation\Business\Navigation\NavigationCreator;
use Spryker\Zed\Navigation\Business\Navigation\NavigationDeleter;
use Spryker\Zed\Navigation\Business\Navigation\NavigationReader;
use Spryker\Zed\Navigation\Business\Navigation\NavigationUpdater;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeCreator;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeDeleter;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeReader;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdater;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeReader;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationQueryContainer getQueryContainer()
 */
class NavigationBusinessFactory extends AbstractBusinessFactory
{

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface
     */
    public function createNavigationCreator()
    {
        return new NavigationCreator();
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationUpdaterInterface
     */
    public function createNavigationUpdater()
    {
        return new NavigationUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationReaderInterface
     */
    public function createNavigationReader()
    {
        return new NavigationReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationDeleterInterface
     */
    public function createNavigationDeleter()
    {
        return new NavigationDeleter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface
     */
    public function createNavigationNodeCreator()
    {
        return new NavigationNodeCreator($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdaterInterface
     */
    public function createNavigationNodeUpdater()
    {
        return new NavigationNodeUpdater($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeReaderInterface
     */
    public function createNavigationNodeReader()
    {
        return new NavigationNodeReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeDeleterInterface
     */
    public function createNavigationNodeDeleter()
    {
        return new NavigationNodeDeleter($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface
     */
    public function createNavigationTreeReader()
    {
        return new NavigationTreeReader($this->getQueryContainer());
    }

}
