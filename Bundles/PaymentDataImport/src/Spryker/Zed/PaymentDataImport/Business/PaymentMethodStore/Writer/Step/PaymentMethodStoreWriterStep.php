<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\Step;

use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PaymentDataImport\Business\PaymentMethodStore\Writer\DataSet\PaymentMethodStoreDataSetInterface;

class PaymentMethodStoreWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        SpyPaymentMethodStoreQuery::create()
            ->filterByFkStore($dataSet[PaymentMethodStoreDataSetInterface::COL_ID_STORE])
            ->filterByFkPaymentMethod($dataSet[PaymentMethodStoreDataSetInterface::COL_ID_PAYMENT_METHOD])
            ->findOneOrCreate()
            ->save();
    }
}
