<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ConfigurableBundleCart\Expander;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;

class ConfiguredBundleGroupKeyExpander implements ConfiguredBundleGroupKeyExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    public function expandConfiguredBundleWithGroupKey(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        $configuredBundleTransfer
            ->requireTemplate()
            ->getTemplate()
                ->requireUuid();

        $configuredBundleTransfer->setGroupKey(
            sprintf('%s-%s', $configuredBundleTransfer->getTemplate()->getUuid(), uniqid('', true))
        );

        return $configuredBundleTransfer;
    }
}
