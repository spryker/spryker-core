<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Generator;

use Generated\Shared\Transfer\ConfiguredBundleRequestTransfer;

class ConfiguredBundleGroupKeyGenerator implements ConfiguredBundleGroupKeyGeneratorInterface
{
    protected const SEPARATOR = '-';

    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer
     *
     * @return string
     */
    public function generateConfiguredBundleGroupKey(ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer): string
    {
        $configuredBundleRequestTransfer->requireTemplateUuid();

        return $this->generateConfiguredBundleGroupKeyByUuid($configuredBundleRequestTransfer->getTemplateUuid());
    }

    /**
     * @param string $uuid
     *
     * @return string
     */
    protected function generateConfiguredBundleGroupKeyByUuid(string $uuid): string
    {
        return $uuid . static::SEPARATOR . time();
    }
}
