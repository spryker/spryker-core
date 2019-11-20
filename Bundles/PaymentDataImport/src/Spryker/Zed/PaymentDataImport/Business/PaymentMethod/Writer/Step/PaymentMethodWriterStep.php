<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\Step;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\DataSet\PaymentMethodDataSetInterface;

class PaymentMethodWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $paymentMethodKey = $dataSet[PaymentMethodDataSetInterface::COL_PAYMENT_METHOD_KEY];

        if (!$paymentMethodKey) {
            throw new DataKeyNotFoundInDataSetException('Payment method key is missing');
        }
        $paymentMethodEntity = SpyPaymentMethodQuery::create()
            ->filterByPaymentMethodKey($paymentMethodKey)
            ->findOneOrCreate();

        $paymentMethodEntity->setFkPaymentProvider($dataSet[PaymentMethodDataSetInterface::COL_ID_PAYMENT_PROVIDER])
            ->setName($dataSet[PaymentMethodDataSetInterface::COL_PAYMENT_METHOD_NAME])
            ->setIsActive($dataSet[PaymentMethodDataSetInterface::COL_IS_ACTIVE])
            ->save();
    }
}
