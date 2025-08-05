<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SelfServicePortal\Business\SspModel\Validator;

use Generated\Shared\Transfer\SspModelCollectionResponseTransfer;
use Generated\Shared\Transfer\SspModelTransfer;

interface SspModelValidatorInterface
{
    public function validateModelTransfer(
        SspModelTransfer $sspModelTransfer,
        SspModelCollectionResponseTransfer $sspModelCollectionResponseTransfer
    ): bool;
}
