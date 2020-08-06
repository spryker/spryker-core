<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\DataSet;

interface PaymentMethodDataSetInterface
{
    public const COL_PAYMENT_METHOD_KEY = 'payment_method_key';
    public const COL_PAYMENT_METHOD_NAME = 'payment_method_name';
    public const COL_PAYMENT_PROVIDER_KEY = 'payment_provider_key';
    public const COL_PAYMENT_PROVIDER_NAME = 'payment_provider_name';
    public const COL_IS_ACTIVE = 'is_active';
    public const COL_ID_PAYMENT_PROVIDER = 'id_payment_provider';
}
