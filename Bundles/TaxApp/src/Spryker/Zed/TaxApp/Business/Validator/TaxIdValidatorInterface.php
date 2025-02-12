<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Business\Validator;

use Generated\Shared\Transfer\TaxAppValidationRequestTransfer;
use Generated\Shared\Transfer\TaxAppValidationResponseTransfer;

interface TaxIdValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppValidationResponseTransfer
     */
    public function validate(TaxAppValidationRequestTransfer $taxAppValidationRequestTransfer): TaxAppValidationResponseTransfer;
}
