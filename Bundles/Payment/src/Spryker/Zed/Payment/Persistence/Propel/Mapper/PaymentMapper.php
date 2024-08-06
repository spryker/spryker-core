<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentMethodCollectionTransfer;
use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethod;
use Propel\Runtime\Collection\Collection;

class PaymentMapper
{
    /**
     * @var \Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentProviderMapper
     */
    protected $paymentProviderMapper;

    /**
     * @var \Spryker\Zed\Payment\Persistence\Propel\Mapper\StoreRelationMapper
     */
    protected $storeRelationMapper;

    /**
     * @param \Spryker\Zed\Payment\Persistence\Propel\Mapper\PaymentProviderMapper $paymentProviderMapper
     * @param \Spryker\Zed\Payment\Persistence\Propel\Mapper\StoreRelationMapper $storeRelationMapper
     */
    public function __construct(
        PaymentProviderMapper $paymentProviderMapper,
        StoreRelationMapper $storeRelationMapper
    ) {
        $this->paymentProviderMapper = $paymentProviderMapper;
        $this->storeRelationMapper = $storeRelationMapper;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Payment\Persistence\SpyPaymentMethod> $paymentMethodEntityCollection
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function mapPaymentMethodEntityCollectionToPaymentMethodsTransfer(
        Collection $paymentMethodEntityCollection,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): PaymentMethodsTransfer {
        foreach ($paymentMethodEntityCollection as $paymentMethodEntity) {
            $paymentMethodsTransfer->addMethod($this->mapPaymentMethodEntityToPaymentMethodTransfer(
                $paymentMethodEntity,
                new PaymentMethodTransfer(),
            ));
        }

        return $paymentMethodsTransfer;
    }

    /**
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodTransfer
     */
    public function mapPaymentMethodEntityToPaymentMethodTransfer(
        SpyPaymentMethod $paymentMethodEntity,
        PaymentMethodTransfer $paymentMethodTransfer
    ): PaymentMethodTransfer {
        $paymentMethodData = $paymentMethodEntity->toArray();

        if (isset($paymentMethodData['payment_method_app_configuration'])) {
            $paymentMethodData['payment_method_app_configuration'] = json_decode($paymentMethodData['payment_method_app_configuration'], true);
        }

        $paymentMethodTransfer->fromArray($paymentMethodData, true);

        /** @deprecated property usage for BC */
        $paymentMethodTransfer->setMethodName($paymentMethodEntity->getPaymentMethodKey());

        $paymentProviderTransfer = $this->paymentProviderMapper->mapPaymentProviderEntityToPaymentProviderTransfer(
            $paymentMethodEntity->getSpyPaymentProvider(),
            new PaymentProviderTransfer(),
        );

        $paymentMethodTransfer->setPaymentProvider($paymentProviderTransfer);
        $paymentMethodTransfer->setIdPaymentProvider($paymentProviderTransfer->getIdPaymentProvider());

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($paymentMethodEntity->getIdPaymentMethod());
        $paymentMethodTransfer->setStoreRelation(
            $this->storeRelationMapper->mapPaymentMethodStoreEntitiesToStoreRelationTransfer(
                $paymentMethodEntity->getSpyPaymentMethodStores(),
                $storeRelationTransfer,
            ),
        );

        return $paymentMethodTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentMethodTransfer $paymentMethodTransfer
     * @param \Orm\Zed\Payment\Persistence\SpyPaymentMethod $paymentMethodEntity
     *
     * @return \Orm\Zed\Payment\Persistence\SpyPaymentMethod
     */
    public function mapPaymentMethodTransferToPaymentMethodEntity(
        PaymentMethodTransfer $paymentMethodTransfer,
        SpyPaymentMethod $paymentMethodEntity
    ): SpyPaymentMethod {
        $paymentMethodData = $paymentMethodTransfer->modifiedToArray();

        if (isset($paymentMethodData['payment_method_app_configuration'])) {
            $paymentMethodData['payment_method_app_configuration'] = json_encode($paymentMethodData['payment_method_app_configuration']);
        }

        $paymentMethodEntity->fromArray($paymentMethodData);

        if ($paymentMethodTransfer->getIdPaymentProvider()) {
            $paymentMethodEntity->setFkPaymentProvider($paymentMethodTransfer->getIdPaymentProvider());
        }

        $paymentMethodEntity->setPaymentMethodKey($paymentMethodTransfer->getPaymentMethodKey() ?? $paymentMethodTransfer->getMethodName());

        return $paymentMethodEntity;
    }

    /**
     * @param \Propel\Runtime\Collection\Collection<\Orm\Zed\Payment\Persistence\SpyPaymentMethod> $paymentMethodEntityCollection
     * @param \Generated\Shared\Transfer\PaymentMethodCollectionTransfer $paymentMethodCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodCollectionTransfer
     */
    public function mapPaymentMethodEntityCollectionToPaymentMethodCollectionTransfer(
        Collection $paymentMethodEntityCollection,
        PaymentMethodCollectionTransfer $paymentMethodCollectionTransfer
    ): PaymentMethodCollectionTransfer {
        foreach ($paymentMethodEntityCollection as $paymentMethodEntity) {
            $paymentMethodTransfer = $this->mapPaymentMethodEntityToPaymentMethodTransfer(
                $paymentMethodEntity,
                new PaymentMethodTransfer(),
            );

            $paymentMethodCollectionTransfer->addPaymentMethod($paymentMethodTransfer);
        }

        return $paymentMethodCollectionTransfer;
    }
}
