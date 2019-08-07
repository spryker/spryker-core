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
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Expander\PriceProductScheduleExpanderInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface;

class PriceProductScheduleCreator implements PriceProductScheduleCreatorInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface
     */
    protected $priceProductScheduleWriter;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface
     */
    protected $priceProductScheduleApplierByProductTypeResolver;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Expander\PriceProductScheduleExpanderInterface
     */
    protected $priceProductScheduleExpander;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleWriterInterface $priceProductScheduleWriter
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Expander\PriceProductScheduleExpanderInterface $priceProductScheduleExpander
     */
    public function __construct(
        PriceProductScheduleWriterInterface $priceProductScheduleWriter,
        PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver,
        PriceProductScheduleExpanderInterface $priceProductScheduleExpander
    ) {
        $this->priceProductScheduleWriter = $priceProductScheduleWriter;
        $this->priceProductScheduleApplierByProductTypeResolver = $priceProductScheduleApplierByProductTypeResolver;
        $this->priceProductScheduleExpander = $priceProductScheduleExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleResponseTransfer
     */
    public function createAndApplyPriceProductSchedule(PriceProductScheduleTransfer $priceProductScheduleTransfer): PriceProductScheduleResponseTransfer
    {
        $priceProductScheduleTransfer = $this->priceProductScheduleExpander
            ->expandPriceProductScheduleTransferWithPriceProductScheduleList($priceProductScheduleTransfer);

        return $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): PriceProductScheduleResponseTransfer {
            return $this->executeCreateLogicTransaction($priceProductScheduleTransfer);
        });
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

        $priceProductScheduleTransfer = $this->priceProductScheduleWriter
            ->createPriceProductSchedule($priceProductScheduleTransfer);
        $priceProductScheduleResponseTransfer = (new PriceProductScheduleResponseTransfer())
            ->setPriceProductSchedule($priceProductScheduleTransfer);
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
            ->setMessage('Schedule price haven not been saved');

        return $priceProductScheduleResponseTransfer->addError($priceProductScheduleErrorTransfer);
    }
}
