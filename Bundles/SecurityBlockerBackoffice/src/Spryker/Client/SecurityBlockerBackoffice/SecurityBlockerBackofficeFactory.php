<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerBackoffice;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\SecurityBlockerBackoffice\Expander\BackofficeConfigurationSettingsExpander;
use Spryker\Client\SecurityBlockerBackoffice\Expander\BackofficeConfigurationSettingsExpanderInterface;

/**
 * @method \Spryker\Client\SecurityBlockerBackoffice\SecurityBlockerBackofficeConfig getConfig()
 */
class SecurityBlockerBackofficeFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\SecurityBlockerBackoffice\Expander\BackofficeConfigurationSettingsExpanderInterface
     */
    public function createBackofficeConfigurationSettingsExpander(): BackofficeConfigurationSettingsExpanderInterface
    {
        return new BackofficeConfigurationSettingsExpander(
            $this->getConfig(),
        );
    }
}
