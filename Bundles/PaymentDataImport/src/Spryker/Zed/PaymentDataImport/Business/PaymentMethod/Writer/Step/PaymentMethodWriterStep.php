<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
        $paymentMethodName = $dataSet[PaymentMethodDataSetInterface::COL_PAYMENT_METHOD_NAME];

        if (!$paymentMethodKey) {
            throw new DataKeyNotFoundInDataSetException('Payment method key is missing');
        }

        if (!$paymentMethodName) {
            throw new DataKeyNotFoundInDataSetException('Payment method name is missing');
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
