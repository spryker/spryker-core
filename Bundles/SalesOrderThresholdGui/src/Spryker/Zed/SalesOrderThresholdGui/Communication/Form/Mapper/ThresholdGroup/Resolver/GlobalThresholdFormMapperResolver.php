<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\Resolver;

use Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingGlobalThresholdFormMapperException;
use Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface;
use Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface;
use Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig;

class GlobalThresholdFormMapperResolver implements GlobalThresholdFormMapperResolverInterface
{
    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig
     */
    protected $config;

    /**
     * @var \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[]
     */
    protected $formExpanderPlugins;

    /**
     * @param \Spryker\Zed\SalesOrderThresholdGui\Dependency\Facade\SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade
     * @param \Spryker\Zed\SalesOrderThresholdGui\SalesOrderThresholdGuiConfig $config
     * @param \Spryker\Zed\SalesOrderThresholdGuiExtension\Dependency\Plugin\SalesOrderThresholdFormExpanderPluginInterface[] $formExpanderPlugins
     */
    public function __construct(
        SalesOrderThresholdGuiToLocaleFacadeInterface $localeFacade,
        SalesOrderThresholdGuiConfig $config,
        array $formExpanderPlugins
    ) {
        $this->localeFacade = $localeFacade;
        $this->config = $config;
        $this->formExpanderPlugins = $formExpanderPlugins;
    }

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @throws \Spryker\Zed\SalesOrderThresholdGui\Communication\Exception\MissingGlobalThresholdFormMapperException
     *
     * @return \Spryker\Zed\SalesOrderThresholdGui\Communication\Form\Mapper\ThresholdGroup\GlobalThresholdFormMapperInterface
     */
    public function resolveGlobalThresholdFormMapperClassInstanceByStrategyGroup(string $salesOrderThresholdTypeGroup): GlobalThresholdFormMapperInterface
    {
        if (!$this->hasGlobalThresholdFormMapperByStrategyGroup($salesOrderThresholdTypeGroup)) {
            throw new MissingGlobalThresholdFormMapperException();
        }

        $mapperClass = $this->config->getStrategyGroupToFormTypeMap()[$salesOrderThresholdTypeGroup];

        return new $mapperClass($this->localeFacade, $this->formExpanderPlugins);
    }

    /**
     * @param string $salesOrderThresholdTypeGroup
     *
     * @return bool
     */
    public function hasGlobalThresholdFormMapperByStrategyGroup(string $salesOrderThresholdTypeGroup): bool
    {
        return array_key_exists($salesOrderThresholdTypeGroup, $this->config->getStrategyGroupToFormTypeMap());
    }
}
