<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImportMerchant\Business\Validator\Rule;

/**
 * Interface for validator rules that should stop validation chain on errors.
 * When implemented, the validation process will stop if errors were found during rule validation.
 */
interface TerminationAwareValidatorRuleInterface
{
}
