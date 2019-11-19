<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ConfigurableBundleCart\Validator;

use Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer;

interface ConfiguredBundleValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer
     *
     * @return bool
     */
    public function validateCreateConfiguredBundleRequestTransfer(CreateConfiguredBundleRequestTransfer $createConfiguredBundleRequestTransfer): bool;
}
