<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerExtension\Dependency\Plugin;

/**
 * Provides extension capabilities for security blocker functional.
 *
 * Use this plugin if some entity type needs to be blocked based on the number of failed login attempts.
 */
interface SecurityBlockerConfigurationSettingsExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands security blocker configuration settings with new entity types.
     *
     * @api
     *
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expand(array $securityBlockerConfigurationSettingsTransfers): array;
}
