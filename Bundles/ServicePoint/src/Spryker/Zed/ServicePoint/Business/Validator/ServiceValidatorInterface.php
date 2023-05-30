<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use Generated\Shared\Transfer\ServiceCollectionResponseTransfer;

interface ServiceValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionResponseTransfer
     */
    public function validate(
        ServiceCollectionResponseTransfer $serviceCollectionResponseTransfer
    ): ServiceCollectionResponseTransfer;
}
