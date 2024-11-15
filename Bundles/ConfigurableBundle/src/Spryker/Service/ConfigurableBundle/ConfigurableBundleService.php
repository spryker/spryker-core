<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ConfigurableBundle;

use Generated\Shared\Transfer\ConfiguredBundleTransfer;
use Spryker\Service\Kernel\AbstractService;

/**
 * @method \Spryker\Service\ConfigurableBundle\ConfigurableBundleServiceFactory getFactory()
 */
class ConfigurableBundleService extends AbstractService implements ConfigurableBundleServiceInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ConfiguredBundleTransfer $configuredBundleTransfer
     *
     * @return \Generated\Shared\Transfer\ConfiguredBundleTransfer
     */
    public function expandConfiguredBundleWithGroupKey(ConfiguredBundleTransfer $configuredBundleTransfer): ConfiguredBundleTransfer
    {
        return $this->getFactory()
            ->createConfiguredBundleGroupKeyExpander()
            ->expandConfiguredBundleWithGroupKey($configuredBundleTransfer);
    }
}
