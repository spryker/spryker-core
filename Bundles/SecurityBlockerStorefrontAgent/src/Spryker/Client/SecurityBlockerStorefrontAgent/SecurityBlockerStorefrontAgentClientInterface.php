<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlockerStorefrontAgent;

interface SecurityBlockerStorefrontAgentClientInterface
{
    /**
     * Specification:
     * - Expands security blocker configuration settings with agent user settings.
     *
     * @api
     *
     * @param array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer> $securityBlockerConfigurationSettingsTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer>
     */
    public function expandSecurityBlockerConfigurationsWithAgentConfiguration(array $securityBlockerConfigurationSettingsTransfers): array;
}
