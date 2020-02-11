<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Creator;

use Generated\Shared\Transfer\PriceProductScheduleErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListCreatorInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductScheduleCreator implements PriceProductScheduleCreatorInterface
{
    use TransactionTrait;

    protected const MESSAGE_ERROR_CREATE_SCHEDULED_PRICE = 'Scheduled price creation failed';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface
     */
    protected $priceProductScheduleApplierByProductTypeResolver;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface
     */
    protected $priceProductScheduleListFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListCreatorInterface
     */
    protected $priceProductScheduleListCreator;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface $priceProductScheduleListFinder
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListCreatorInterface $priceProductScheduleListCreator
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver,
        PriceProductScheduleListFinderInterface $priceProductScheduleListFinder,
        PriceProductScheduleListCreatorInterface $priceProductScheduleListCreator
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleApplierByProductTypeResolver = $priceProductScheduleApplierByProductTypeResolver;
        $this->priceProductScheduleListFinder = $priceProductScheduleListFinder;
        $this->priceProductScheduleListCreator = $priceProductScheduleListCreator;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleTransfer = $this
            ->expandPriceProductScheduleTransferWithPriceProductScheduleList($priceProductScheduleTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): PriceProductScheduleResponseTransfer {
            return $this->executeCreateLogicTransaction($priceProductScheduleTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    protected function expandPriceProductScheduleTransferWithPriceProductScheduleList(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductScheduleListTransfer = $this->priceProductScheduleListFinder
            ->findDefaultPriceProductScheduleList();

        if ($priceProductScheduleListTransfer === null) {
            $priceProductScheduleListTransfer = $this->priceProductScheduleListCreator
                ->createDefaultPriceProductScheduleList();
        }

        return $priceProductScheduleTransfer->setPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    protected function executeCreateLogicTransaction(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleResponseTransfer = new PriceProductScheduleResponseTransfer();

        if ($priceProductScheduleTransfer->getPriceProductScheduleList() === null) {
            $priceProductScheduleResponseTransfer->setIsSuccess(false);

            return $this->addErrorMessage($priceProductScheduleResponseTransfer);
        }

        $priceProductScheduleTransfer = $this->priceProductScheduleEntityManager
            ->createPriceProductSchedule($priceProductScheduleTransfer);
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
            ->setMessage(static::MESSAGE_ERROR_CREATE_SCHEDULED_PRICE);

        return $priceProductScheduleResponseTransfer->addError($priceProductScheduleErrorTransfer);
    }
}
