<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ConfigurableBundleCartsRestApi\Business\Generator;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;

class ConfiguredBundleGroupKeyGenerator implements ConfiguredBundleGroupKeyGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return string
     */
    public function generateConfiguredBundleGroupKeyByUuid(ConfiguredBundleTransfer $configuredBundleTransfer): string
    {
        $configuredBundleTransfer
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid();

        return sprintf('%s-%s', $configuredBundleTransfer->getTemplate()->getUuid(), uniqid('', true));
    }
}
