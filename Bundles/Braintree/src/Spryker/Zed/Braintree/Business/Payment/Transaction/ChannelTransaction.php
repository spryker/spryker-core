<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Braintree\Business\Payment\Transaction;

use Spryker\Shared\Braintree\BraintreeConstants;
use Spryker\Zed\Braintree\Business\Payment\Method\ApiConstants;

final class ChannelTransaction
{

    /**
     * @return string
     */
    final public static function getChannel()
    {
        return ApiConstants::CHANNEL . BraintreeConstants::BUNDLE_VERSION;
    }

}
