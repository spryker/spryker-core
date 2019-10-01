<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ConfigurableBundleCart\ConfigurableBundleCartFactory getFactory()
 */
class ConfigurableBundleCartClient extends AbstractClient implements ConfigurableBundleCartClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function removeConfiguredBundle(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartWriter()
            ->removeConfiguredBundle($updateConfiguredBundleRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function updateConfiguredBundleQuantity(UpdateConfiguredBundleRequestTransfer $updateConfiguredBundleRequestTransfer): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createCartWriter()
            ->updateConfiguredBundleQuantity($updateConfiguredBundleRequestTransfer);
    }
}
