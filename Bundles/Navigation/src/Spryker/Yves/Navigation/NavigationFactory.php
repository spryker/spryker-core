<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Navigation;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Navigation\Twig\NavigationTwigExtension;

/**
 * @method \Spryker\Client\Navigation\NavigationClientInterface getClient()
 */
class NavigationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Yves\Navigation\Twig\NavigationTwigExtension
     */
    public function createNavigationTwigExtension()
    {
        return new NavigationTwigExtension($this->getClient(), $this->createApplication());
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function createApplication()
    {
        return $this->getProvidedDependency(NavigationDependencyProvider::PLUGIN_APPLICATION);
    }
}
