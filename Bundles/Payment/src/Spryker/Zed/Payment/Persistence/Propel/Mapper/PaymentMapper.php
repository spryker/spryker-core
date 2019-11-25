<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payment\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\PaymentProviderTransfer;
use Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethod;
use Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType;

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
     * @param \Orm\Zed\Payment\Persistence\SpySalesPaymentMethodType $productPackagingUnitEntity
     * @param \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
     *
     * @return \Generated\Shared\Transfer\SalesPaymentMethodTypeTransfer
     */
    public function mapSalesPaymentMethodTypeTransfer(
        SpySalesPaymentMethodType $productPackagingUnitEntity,
        SalesPaymentMethodTypeTransfer $salesPaymentMethodTypeTransfer
    ): SalesPaymentMethodTypeTransfer {
        $salesPaymentMethodTypeTransfer->fromArray($productPackagingUnitEntity->toArray(), true);
        $paymentProviderTransfer = (new PaymentProviderTransfer())
            ->setName($productPackagingUnitEntity->getPaymentProvider());
        $salesPaymentMethodTypeTransfer->setPaymentProvider($paymentProviderTransfer);

        $paymentMethodTransfer = (new PaymentMethodTransfer())
            ->setMethodName($productPackagingUnitEntity->getPaymentMethod());
        $salesPaymentMethodTypeTransfer->setPaymentMethod($paymentMethodTransfer);

        return $salesPaymentMethodTypeTransfer;
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
    ): PaymentMethodTransfer
    {
        $paymentMethodTransfer->fromArray($paymentMethodEntity->toArray(), true);
        $paymentMethodTransfer->setMethodName($paymentMethodEntity->getName());

        $paymentMethodTransfer->setPaymentProvider(
            $this->paymentProviderMapper->mapPaymentProviderEntityToPaymentProviderTransfer(
                $paymentMethodEntity->getPaymentProvider(),
                new PaymentProviderTransfer()
            )
        );

        $storeRelationTransfer = (new StoreRelationTransfer())
            ->setIdEntity($paymentMethodEntity->getIdPaymentMethod());
        $paymentMethodTransfer->setStoreRelation(
            $this->storeRelationMapper->mapShipmentMethodStoreEntitiesToStoreRelationTransfer(
                $paymentMethodEntity->getPaymentMethodStores(),
                $storeRelationTransfer
            )
        );

        return $paymentMethodTransfer;
    }
}
