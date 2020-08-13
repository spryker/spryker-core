<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentMethodsTransfer;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethod;
use Propel\Runtime\Collection\ObjectCollection;

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
     * @param \Propel\Runtime\Collection\ObjectCollection $paymentMethodEntityCollection
     * @param \Generated\Shared\Transfer\PaymentMethodsTransfer $paymentMethodsTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentMethodsTransfer
     */
    public function mapPaymentMethodEntityCollectionToPaymentMethodsTransfer(
        ObjectCollection $paymentMethodEntityCollection,
        PaymentMethodsTransfer $paymentMethodsTransfer
    ): PaymentMethodsTransfer {
        foreach ($paymentMethodEntityCollection as $paymentMethodEntity) {
            $paymentMethodsTransfer->addMethod($this->mapPaymentMethodEntityToPaymentMethodTransfer(
                $paymentMethodEntity,
                new PaymentMethodTransfer()
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
        $paymentMethodTransfer->fromArray($paymentMethodEntity->toArray(), true);
        $paymentMethodTransfer->setMethodName($paymentMethodEntity->getPaymentMethodKey());

        $paymentMethodTransfer->setPaymentProvider(
            $this->paymentProviderMapper->mapPaymentProviderEntityToPaymentProviderTransfer(
                $paymentMethodEntity->getSpyPaymentProvider(),
                new PaymentProviderTransfer()
            )
        );

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($paymentMethodEntity->getIdPaymentMethod());
        $paymentMethodTransfer->setStoreRelation(
            $this->storeRelationMapper->mapPaymentMethodStoreEntitiesToStoreRelationTransfer(
                $paymentMethodEntity->getSpyPaymentMethodStores(),
                $storeRelationTransfer
            )
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
        $paymentMethodEntity->fromArray($paymentMethodTransfer->modifiedToArray());
        $paymentMethodEntity->setPaymentMethodKey($paymentMethodTransfer->getMethodName());

        return $paymentMethodEntity;
    }
}
