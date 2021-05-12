<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SecurityBlocker\Resolver;

use Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer;

interface ConfigurationResolverInterface
{
    /**
     * @param string $type
     *
     * @return \Generated\Shared\Transfer\SecurityBlockerConfigurationSettingsTransfer
     */
    public function getSecurityBlockerConfigurationSettingsForType(string $type): SecurityBlockerConfigurationSettingsTransfer;
}
