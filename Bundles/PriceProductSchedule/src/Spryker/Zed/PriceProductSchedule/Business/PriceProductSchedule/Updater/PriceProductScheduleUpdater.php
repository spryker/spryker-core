<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Updater;

use Generated\Shared\Transfer\PriceProductScheduleErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductScheduleUpdater implements PriceProductScheduleUpdaterInterface
{
    use TransactionTrait;

    protected const MESSAGE_ERROR_SAVE_SCHEDULED_PRICE = 'Failed to save price schedule';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface
     */
    protected $priceProductScheduleApplierByProductTypeResolver;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleApplierByProductTypeResolver = $priceProductScheduleApplierByProductTypeResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function updateAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): PriceProductScheduleResponseTransfer {
            return $this->executeUpdateAndApplyLogicTransaction($priceProductScheduleTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    protected function executeUpdateAndApplyLogicTransaction(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleResponseTransfer = new PriceProductScheduleResponseTransfer();
        $priceProductScheduleTransfer->requirePriceProductScheduleList();
        $priceProductScheduleTransfer = $this->priceProductScheduleEntityManager
            ->savePriceProductSchedule($priceProductScheduleTransfer);
        $priceProductScheduleResponseTransfer->setPriceProductSchedule($priceProductScheduleTransfer);
        $this->priceProductScheduleApplierByProductTypeResolver
            ->applyPriceProductScheduleByProductType($priceProductScheduleTransfer);

        if ($priceProductScheduleTransfer->getIdPriceProductSchedule() !== null) {
            return $priceProductScheduleResponseTransfer->setIsSuccess(true);
        }

        $priceProductScheduleResponseTransfer->setIsSuccess(false);

        return $this->addErrorMessage($priceProductScheduleResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer $priceProductScheduleResponseTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    protected function addErrorMessage(PriceProductScheduleResponseTransfer $priceProductScheduleResponseTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleErrorTransfer = (new PriceProductScheduleErrorTransfer())
            ->setMessage(static::MESSAGE_ERROR_SAVE_SCHEDULED_PRICE);

        return $priceProductScheduleResponseTransfer->addError($priceProductScheduleErrorTransfer);
    }
}
