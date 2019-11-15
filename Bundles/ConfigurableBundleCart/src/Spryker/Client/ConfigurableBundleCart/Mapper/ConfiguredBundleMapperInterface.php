<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Mapper;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;

interface ConfiguredBundleMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function mapCreateConfiguredBundleRequestTransferToCartChangeTransfer(
        CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer,
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer;
}
