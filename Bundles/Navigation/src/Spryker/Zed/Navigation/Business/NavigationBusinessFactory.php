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

}
