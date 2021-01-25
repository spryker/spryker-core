<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Navigation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\Navigation\Business\Navigation\NavigationCreator;
use Spryker\Zed\Navigation\Business\Navigation\NavigationDeleter;
use Spryker\Zed\Navigation\Business\Navigation\NavigationDuplicator;
use Spryker\Zed\Navigation\Business\Navigation\NavigationDuplicatorInterface;
use Spryker\Zed\Navigation\Business\Navigation\NavigationReader;
use Spryker\Zed\Navigation\Business\Navigation\NavigationTouch;
use Spryker\Zed\Navigation\Business\Navigation\NavigationUpdater;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeCreator;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeDeleter;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeReader;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeTouch;
use Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdater;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeHierarchyUpdater;
use Spryker\Zed\Navigation\Business\Tree\NavigationTreeReader;
use Spryker\Zed\Navigation\Business\Url\NavigationNodeUrlCleaner;
use Spryker\Zed\Navigation\NavigationDependencyProvider;

/**
 * @method \Spryker\Zed\Navigation\Persistence\NavigationQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Navigation\NavigationConfig getConfig()
 * @method \Spryker\Zed\Navigation\Persistence\NavigationRepositoryInterface getRepository()
 */
class NavigationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationCreatorInterface
     */
    public function createNavigationCreator()
    {
        return new NavigationCreator($this->getQueryContainer(), $this->createNavigationTouch());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationDuplicatorInterface
     */
    public function createNavigationDuplicator(): NavigationDuplicatorInterface
    {
        return new NavigationDuplicator(
            $this->createNavigationTreeReader(),
            $this->getRepository(),
            $this->createNavigationCreator(),
            $this->createNavigationNodeCreator()
        );
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationUpdaterInterface
     */
    public function createNavigationUpdater()
    {
        return new NavigationUpdater($this->getQueryContainer(), $this->createNavigationTouch());
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
        return new NavigationDeleter($this->getQueryContainer(), $this->createNavigationTouch());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeCreatorInterface
     */
    public function createNavigationNodeCreator()
    {
        return new NavigationNodeCreator($this->getQueryContainer(), $this->createNavigationNodeTouch());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeUpdaterInterface
     */
    public function createNavigationNodeUpdater()
    {
        return new NavigationNodeUpdater($this->getQueryContainer(), $this->createNavigationNodeTouch());
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
        return new NavigationNodeDeleter($this->getQueryContainer(), $this->createNavigationNodeTouch());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Tree\NavigationTreeReaderInterface
     */
    public function createNavigationTreeReader()
    {
        return new NavigationTreeReader($this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Tree\NavigationTreeHierarchyUpdaterInterface
     */
    public function createNavigationTreeHierarchyUpdater()
    {
        return new NavigationTreeHierarchyUpdater($this->getQueryContainer(), $this->createNavigationTouch());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Navigation\NavigationTouchInterface
     */
    public function createNavigationTouch()
    {
        return new NavigationTouch($this->getTouchFacade(), $this->getQueryContainer());
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Node\NavigationNodeTouchInterface
     */
    public function createNavigationNodeTouch()
    {
        return new NavigationNodeTouch($this->createNavigationTouch());
    }

    /**
     * @return \Spryker\Zed\Navigation\Dependency\Facade\NavigationToTouchInterface
     */
    public function getTouchFacade()
    {
        return $this->getProvidedDependency(NavigationDependencyProvider::FACADE_TOUCH);
    }

    /**
     * @return \Spryker\Zed\Navigation\Business\Url\NavigationNodeUrlCleanerInterface
     */
    public function createNavigationNodeUrlCleaner()
    {
        return new NavigationNodeUrlCleaner($this->getQueryContainer(), $this->createNavigationNodeUpdater());
    }
}
