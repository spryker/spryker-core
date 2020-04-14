<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\DataSet;

interface PaymentMethodStoreDataSetInterface
{
    public const COL_PAYMENT_METHOD_KEY = 'payment_method_key';
    public const COL_STORE = 'store';
    public const COL_ID_PAYMENT_METHOD = 'fk_payment_method';
    public const COL_ID_STORE = 'fk_store';
}
