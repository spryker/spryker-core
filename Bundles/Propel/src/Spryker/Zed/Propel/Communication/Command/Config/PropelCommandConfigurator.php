<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Propel\Communication\Command\Config;

use Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface;

class PropelCommandConfigurator implements PropelCommandConfiguratorInterface
{
    protected const KEY_CONFIG_PROPEL_GENERATOR = 'generator';
    protected const KEY_CONFIG_PROPEL_NAMESPACE_AUTO_PACKAGE = 'namespaceAutoPackage';

    /**
     * @var array
     */
    protected $propelConfig;

    /**
     * @param array $propelConfig
     */
    public function __construct(array $propelConfig)
    {
        $this->propelConfig = $propelConfig;
    }

    /**
     * @param \Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface $propelCommand
     *
     * @return \Spryker\Zed\PropelOrm\Business\Generator\PropelConfigurableInterface
     */
    public function propelConfigurable(PropelConfigurableInterface $propelCommand): PropelConfigurableInterface
    {
        return $propelCommand->setPropelConfig($this->buildPropelConfig());
    }

    /**
     * @return array
     */
    protected function buildPropelConfig(): array
    {
        $propelConfig = $this->propelConfig;
        $propelConfig[static::KEY_CONFIG_PROPEL_GENERATOR][static::KEY_CONFIG_PROPEL_NAMESPACE_AUTO_PACKAGE] = false;

        return $propelConfig;
    }
}
