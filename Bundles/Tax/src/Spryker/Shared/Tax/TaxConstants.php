<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Tax;

interface TaxConstants
{
    const DEFAULT_TAX_RATE = 'DEFAULT_TAX_RATE';
    const TAX_EXEMPT_PLACEHOLDER = 'Tax Exempt';

    const ERROR_MESSAGE_TAX_SET_EXISTS = 'Tax set with the same name already exists. Please choose a different name.';
    const ERROR_MESSAGE_TAX_SET_CREATE_ERROR = 'Tax set is not created. Please fill-in all required fields.';
    const ERROR_MESSAGE_TAX_SET_UPDATE_ERROR = 'Tax set is not updated. Please fill-in all required fields.';
    const SUCCESS_MESSAGE_TAX_SET_CREATED = 'Tax set %d was created successfully.';
    const SUCCESS_MESSAGE_TAX_SET_UPDATED = 'Tax set %d was updated successfully.';
}
