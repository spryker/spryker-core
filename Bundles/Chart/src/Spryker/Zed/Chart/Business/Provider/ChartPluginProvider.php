<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Chart\Business\Provider;

use Spryker\Zed\Chart\Business\Provider\Exception\ChartPluginNotFoundException;

class ChartPluginProvider implements ChartPluginProviderInterface
{
    /**
     * @var \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected $pluginCollection;

    /**
     * @param \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[] $chartPlugins
     */
    public function __construct(array $chartPlugins)
    {
        $this->pluginCollection = $this->buildChartPluginCollection($chartPlugins);
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Chart\Business\Provider\Exception\ChartPluginNotFoundException
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface
     */
    public function getChartPluginByName($name)
    {
        if (!array_key_exists($name, $this->pluginCollection)) {
            throw new ChartPluginNotFoundException(
                sprintf('Chart plugin "%s" was not found', $name)
            );
        }

        return $this->pluginCollection[$name];
    }

    /**
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    public function getChartPluginCollection()
    {
        return $this->pluginCollection;
    }

    /**
     * @param \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[] $chartPlugins
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected function buildChartPluginCollection(array $chartPlugins)
    {
        $chartPluginCollection = [];
        foreach ($chartPlugins as $plugin) {
            $chartPluginCollection[$plugin->getName()] = $plugin;
        }

        return $chartPluginCollection;
    }
}
