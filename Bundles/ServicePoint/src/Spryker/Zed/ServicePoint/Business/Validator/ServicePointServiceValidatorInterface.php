<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer;

interface ServicePointServiceValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer
     */
    public function validate(
        ServicePointServiceCollectionResponseTransfer $servicePointServiceCollectionResponseTransfer
    ): ServicePointServiceCollectionResponseTransfer;
}
