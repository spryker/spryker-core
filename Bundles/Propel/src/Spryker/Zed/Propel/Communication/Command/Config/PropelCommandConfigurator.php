<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Config;

use Spryker\Zed\Propel\PropelConfig;
use Spryker\Zed\PropelOrm\Communication\Generator\ConfigurablePropelCommandInterface;

class PropelCommandConfigurator implements PropelCommandConfiguratorInterface
{
    protected const KEY_CONFIG_PROPEL_GENERATOR = 'generator';
    protected const KEY_CONFIG_PROPEL_NAMESPACE_AUTO_PACKAGE = 'namespaceAutoPackage';

    /**
     * @var \Spryker\Zed\Propel\PropelConfig
     */
    protected $propelConfig;

    /**
     * @param \Spryker\Zed\Propel\PropelConfig $propelConfig
     */
    public function __construct(PropelConfig $propelConfig)
    {
        $this->propelConfig = $propelConfig;
    }

    /**
     * @param \Spryker\Zed\PropelOrm\Communication\Generator\ConfigurablePropelCommandInterface $configurablePropelCommand
     *
     * @return void
     */
    public function configurePropelCommand(ConfigurablePropelCommandInterface $configurablePropelCommand): void
    {
        $configurablePropelCommand->setPropelConfig($this->buildPropelConfig());
    }

    /**
     * @return array
     */
    protected function buildPropelConfig(): array
    {
        $propelConfig = $this->propelConfig->getPropelConfig();
        $propelConfig[static::KEY_CONFIG_PROPEL_GENERATOR][static::KEY_CONFIG_PROPEL_NAMESPACE_AUTO_PACKAGE] = false;

        return $propelConfig;
    }
}
