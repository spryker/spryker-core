<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Braintree;

interface BraintreeConstants
{
    const PROVIDER_NAME = 'Braintree';

    const PAYMENT_METHOD_PAY_PAL = 'braintreePayPal';
    const PAYMENT_METHOD_CREDIT_CARD = 'braintreeCreditCard';

    const MERCHANT_ID = 'BRAINTREE_MERCHANT_ID';
    const PUBLIC_KEY = 'BRAINTREE_PUBLIC_KEY';
    const PRIVATE_KEY = 'BRAINTREE_PRIVATE_KEY';

    const ENVIRONMENT = 'BRAINTREE_ENVIRONMENT';

    const ACCOUNT_ID = 'BRAINTREE_ACCOUNT_ID';
    const ACCOUNT_UNIQUE_IDENTIFIER = 'BRAINTREE_ACCOUNT_UNIQUE_IDENTIFIER';

    const IS_3D_SECURE = 'BRAINTREE_IS_3D_SECURE';
    const IS_VAULTED = 'BRAINTREE_IS_VAULTED';

    const METHOD_PAY_PAL = 'BRAINTREE_PAY_PAL';
}
