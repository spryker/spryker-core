<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Service\UtilDataReader\Dependency\YamlReaderBridge;
use Symfony\Component\Yaml\Yaml;

class UtilDataReaderDependencyProvider extends AbstractBundleDependencyProvider
{
    public const YAML_READER = 'yaml reader';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addYamlReader($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addYamlReader(Container $container)
    {
        $container[static::YAML_READER] = function () {
            return new YamlReaderBridge(new Yaml());
        };

        return $container;
    }
}
