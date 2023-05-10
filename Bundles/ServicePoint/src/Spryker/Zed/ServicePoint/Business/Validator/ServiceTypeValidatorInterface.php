<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Validator;

use Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer;

interface ServiceTypeValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer
     */
    public function validate(
        ServiceTypeCollectionResponseTransfer $serviceTypeCollectionResponseTransfer
    ): ServiceTypeCollectionResponseTransfer;
}
