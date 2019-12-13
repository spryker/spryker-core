<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Payment\Helper;

use Codeception\Lib\Interfaces\DependsOnModule;
use Codeception\Module;
use Generated\Shared\DataBuilder\PaymentMethodBuilder;
use Generated\Shared\DataBuilder\PaymentProviderBuilder;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStore;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentProviderQuery;
use SprykerTest\Shared\Store\Helper\StoreDataHelper;

class PaymentDataHelper extends Module implements DependsOnModule
{
    /**
     * @var \SprykerTest\Shared\Store\Helper\StoreDataHelper
     */
    protected $storeDataHelper;

    protected const ERROR_DEPENDENCY_MESSAGE = <<<EOF
Example configuring StoreDataHelper as backend for REST module.
--
modules:
    enabled:
        - \SprykerTest\Shared\Payment\Helper\PaymentDataHelper:
            depends: \SprykerTest\Shared\Store\Helper\StoreDataHelper
--
EOF;

    /**
     * @return array
     */
    public function _depends(): array
    {
        return [StoreDataHelper::class => static::ERROR_DEPENDENCY_MESSAGE];
    }

    /**
     * @param \SprykerTest\Shared\Store\Helper\StoreDataHelper $storeDataHelper
     */
    public function _inject(StoreDataHelper $storeDataHelper)
    {
        $this->storeDataHelper = $storeDataHelper;
    }

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
            ->filterByName($paymentProviderTransfer->getName())
            ->findOneOrCreate();

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

        if (!$paymentMethodTransfer->getIdPaymentProvider() || !$paymentMethodTransfer->getPaymentProvider()) {
            $paymentProviderTransfer = $this->havePaymentProvider();
            $paymentMethodTransfer->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProvider());
            $paymentMethodTransfer->setPaymentProvider($paymentProviderTransfer);
        }

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
            $storeRelationTransfer = new StoreRelationTransfer();
            $defaultStoreId = $this->storeDataHelper->grabDefaultStore()->getIdStore();
            $storeRelationTransfer->setIdStores([$defaultStoreId]);
        }

        foreach ($storeRelationTransfer->getIdStores() as $idStore) {
            (new SpyPaymentMethodStore())
                ->setFkPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
                ->setFkStore($idStore)
                ->save();
        }

        return $paymentMethodTransfer;
    }
}
