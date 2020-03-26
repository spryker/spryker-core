<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Payment\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery;

class PaymentDataHelper extends Module
{
    /**
     * @return void
     */
    public function ensurePaymentMethodTableIsEmpty(): void
    {
        SpyPaymentMethodStoreQuery::create()->deleteAll();
        SpyPaymentMethodQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensurePaymentMethodStoreTableIsEmpty(): void
    {
        SpyPaymentMethodStoreQuery::create()->deleteAll();
    }

    /**
     * @return void
     */
    public function ensurePaymentProviderTableIsEmpty(): void
    {
        SpyPaymentMethodStoreQuery::create()->deleteAll();
        SpyPaymentMethodQuery::create()->deleteAll();
        SpyPaymentProviderQuery::create()->deleteAll();
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentProviderTransfer
     */
    public function havePaymentProvider(array $override = []): PaymentProviderTransfer
    {
        $paymentProviderTransfer = (new PaymentProviderBuilder())->seed($override)->build();

        $paymentProviderEntity = SpyPaymentProviderQuery::create()
            ->filterByPaymentProviderKey($paymentProviderTransfer->getPaymentProviderKey())
            ->findOneOrCreate();

        $paymentProviderEntity->fromArray($paymentProviderTransfer->modifiedToArray());
        $paymentProviderEntity->save();

        $paymentProviderTransfer->setIdPaymentProvider($paymentProviderEntity->getIdPaymentProvider());

        return $paymentProviderTransfer;
    }

    /**
     * @param array $override
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function havePaymentMethod(array $override = []): PaymentMethodTransfer
    {
        $paymentMethodTransfer = (new PaymentMethodBuilder())->seed($override)->build();
        $paymentMethodEntity = SpyPaymentMethodQuery::create()
            ->filterByPaymentMethodKey($paymentMethodTransfer->getMethodName())
            ->filterByName($paymentMethodTransfer->getName())
            ->findOneOrCreate();
        $paymentMethodEntity->setFkPaymentProvider($paymentMethodTransfer->getIdPaymentProvider());
        $paymentMethodEntity->fromArray($paymentMethodTransfer->modifiedToArray());

        $paymentMethodEntity->save();

        $paymentMethodTransfer->setIdPaymentMethod($paymentMethodEntity->getIdPaymentMethod());
        $storeRelationTransfer = $paymentMethodTransfer->getStoreRelation();

        if (!$storeRelationTransfer) {
            return $paymentMethodTransfer;
        }

        foreach ($storeRelationTransfer->getIdStores() as $idStore) {
            SpyPaymentMethodStoreQuery::create()
                ->filterByFkPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
                ->filterByFkStore($idStore)
                ->findOneOrCreate()
                ->save();
        }

        return $paymentMethodTransfer;
    }
}
