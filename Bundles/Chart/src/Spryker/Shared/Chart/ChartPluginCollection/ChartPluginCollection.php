<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Chart\ChartPluginCollection;

use Spryker\Shared\Chart\ChartPluginCollection\Exception\ChartPluginNotFoundException;
use Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface;

class ChartPluginCollection implements ChartPluginCollectionInterface
{
    /**
     * @var \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[]
     */
    protected $chartPlugins;

    /**
     * @param \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface[] $chartPlugins
     */
    public function __construct(array $chartPlugins)
    {
        $this->chartPlugins = $chartPlugins;
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Shared\Chart\ChartPluginCollection\Exception\ChartPluginNotFoundException
     *
     * @return \Spryker\Shared\Chart\Dependency\Plugin\ChartPluginInterface
     */
    public function getChartPluginByName($name): ChartPluginInterface
    {
        foreach ($this->chartPlugins as $chartPlugin) {
            if ($chartPlugin->getName() === $name) {
                return $chartPlugin;
            }
        }

        throw new ChartPluginNotFoundException(sprintf('Chart plugin "%s" was not found', $name));
    }
}
