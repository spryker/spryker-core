<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\Step;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\DataSet\PaymentMethodStoreDataSetInterface;

class PaymentMethodKeyToIdPaymentMethodStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idPaymentMethodCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $paymentMethodKey = $dataSet[PaymentMethodStoreDataSetInterface::COL_PAYMENT_METHOD_KEY];

        if (!$paymentMethodKey) {
            throw new DataKeyNotFoundInDataSetException('Payment method key is missing');
        }

        if (!isset(static::$idPaymentMethodCache[$paymentMethodKey])) {
            $paymentMethodEntity = SpyPaymentMethodQuery::create()
                ->filterByPaymentMethodKey($paymentMethodKey)
                ->findOne();

            if ($paymentMethodEntity === null) {
                throw new EntityNotFoundException(sprintf('Payment method not found: %s', $paymentMethodKey));
            }

            static::$idPaymentMethodCache[$paymentMethodKey] = $paymentMethodEntity->getIdPaymentMethod();
        }

        $dataSet[PaymentMethodStoreDataSetInterface::COL_ID_PAYMENT_METHOD] = static::$idPaymentMethodCache[$paymentMethodKey];
    }
}
