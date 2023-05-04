<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer;

interface ServicePointAddressValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionResponseTransfer
     */
    public function validate(
        ServicePointAddressCollectionResponseTransfer $servicePointAddressCollectionResponseTransfer
    ): ServicePointAddressCollectionResponseTransfer;
}
