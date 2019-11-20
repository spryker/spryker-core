<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Generator;

use Generated\Shared\Transfer\ConfiguredBundleRequestTransfer;

class ConfiguredBundleGroupKeyGenerator implements ConfiguredBundleGroupKeyGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer
     *
     * @return string
     */
    public function generateConfiguredBundleGroupKeyByUuid(ConfiguredBundleRequestTransfer $configuredBundleRequestTransfer): string
    {
        $configuredBundleRequestTransfer->requireTemplateUuid();

        return sprintf('%s-%s', $configuredBundleRequestTransfer->getTemplateUuid(), uniqid('', true));
    }
}
