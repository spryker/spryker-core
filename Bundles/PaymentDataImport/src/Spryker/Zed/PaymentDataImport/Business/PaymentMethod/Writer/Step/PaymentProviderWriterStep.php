<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\Step;

use Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethod\Writer\DataSet\PaymentMethodDataSetInterface;

class PaymentProviderWriterStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected static $idPaymentProviderCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $paymentProviderKey = $dataSet[PaymentMethodDataSetInterface::COL_PAYMENT_PROVIDER_KEY];
        $paymentProviderName = $dataSet[PaymentMethodDataSetInterface::COL_PAYMENT_PROVIDER_NAME];

        if (!$paymentProviderKey) {
            throw new DataKeyNotFoundInDataSetException('Payment provider key is missing');
        }

        if (!$paymentProviderName) {
            throw new DataKeyNotFoundInDataSetException('Payment provider name is missing');
        }

        if (!isset(static::$idPaymentProviderCache[$paymentProviderKey])) {
            $paymentProviderEntity = SpyPaymentProviderQuery::create()
                ->filterByPaymentProviderKey($paymentProviderKey)
                ->findOneOrCreate();
            $paymentProviderEntity->setName($dataSet[PaymentMethodDataSetInterface::COL_PAYMENT_PROVIDER_NAME]);
            $paymentProviderEntity->save();

            static::$idPaymentProviderCache[$paymentProviderKey] = $paymentProviderEntity->getIdPaymentProvider();
        }

        $dataSet[PaymentMethodDataSetInterface::COL_ID_PAYMENT_PROVIDER] = static::$idPaymentProviderCache[$paymentProviderKey];
    }
}
