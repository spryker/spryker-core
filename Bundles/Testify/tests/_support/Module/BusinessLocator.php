<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Testify\Module;

use Codeception\Configuration;
use Codeception\Module;
use Spryker\Shared\Testify\Locator\TestifyConfiguratorInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Spryker\Zed\Testify\Locator\Business\BusinessLocator as Locator;

class BusinessLocator extends Module
{

    /**
     * @var array
     */
    protected $config = [
        'projectNamespaces' => [],
        'coreNamespaces' => [
            'Spryker',
        ],
    ];

    /**
     * @var array
     */
    protected $sprykerConfig = [];

    /**
     * @var array
     */
    protected $dependencies = [];

    /**
     * @return \Spryker\Shared\Kernel\LocatorLocatorInterface|\Generated\Zed\Ide\AutoCompletion|\Generated\Service\Ide\AutoCompletion
     */
    public function getLocator()
    {
        return new Locator($this->config['projectNamespaces'], $this->config['coreNamespaces'], $this->createClosure());
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function setConfig($key, $value)
    {
        $this->sprykerConfig[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return $this
     */
    public function setDependency($key, $value)
    {
        $this->dependencies[$key] = $value;

        return $this;
    }

    /**
     * @return AbstractFacade
     */
    public function getFacade()
    {
        $currentNamespace = Configuration::config()['namespace'];
        $bundleName = lcfirst($currentNamespace);

        return $this->getLocator()->$bundleName()->facade($this->createClosure());
    }

    /**
     * @return \Closure
     */
    private function createClosure()
    {
        $configs = $this->getSprykerConfig();
        $dependencies = $this->getDependencies();
        $callback = function (TestifyConfiguratorInterface $configurator) use ($configs, $dependencies) {
            foreach ($configs as $key => $value) {
                $configurator->getConfig()->set($key, $value);
            }
            foreach ($dependencies as $key => $value) {
                $configurator->getContainer()->set($key, $value);
            }
        };

        return $callback;
    }

    /**
     * @return array
     */
    private function getSprykerConfig()
    {
        $configs = $this->sprykerConfig;
        $this->sprykerConfig = [];

        return $configs;
    }

    /**
     * @return array
     */
    private function getDependencies()
    {
        $dependencies = $this->dependencies;
        $this->dependencies = [];

        return $dependencies;
    }

}
