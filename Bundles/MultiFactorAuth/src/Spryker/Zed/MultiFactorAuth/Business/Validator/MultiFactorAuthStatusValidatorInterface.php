<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MultiFactorAuth\Business\Validator;

use DateTime;
use Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer;
use Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer;

interface MultiFactorAuthStatusValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer
     * @param array<int> $additionalStatuses
     * @param \DateTime|null $currentDateTime
     *
     * @return \Generated\Shared\Transfer\MultiFactorAuthValidationResponseTransfer
     */
    public function validate(
        MultiFactorAuthValidationRequestTransfer $multiFactorAuthValidationRequestTransfer,
        array $additionalStatuses = [],
        ?DateTime $currentDateTime = null
    ): MultiFactorAuthValidationResponseTransfer;
}
