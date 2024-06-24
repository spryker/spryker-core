<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\RuleEngine\Business\Validator;

use Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer;
use Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer;

interface QueryStringValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RuleEngineQueryStringValidationResponseTransfer
     */
    public function validate(
        RuleEngineQueryStringValidationRequestTransfer $ruleEngineQueryStringValidationRequestTransfer
    ): RuleEngineQueryStringValidationResponseTransfer;
}
