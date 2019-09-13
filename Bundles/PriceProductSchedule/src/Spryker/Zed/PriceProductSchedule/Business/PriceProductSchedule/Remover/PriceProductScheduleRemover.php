<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Remover;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleRemover implements PriceProductScheduleRemoverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface
     */
    protected $priceProductScheduleApplierByProductTypeResolver;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface
     */
    protected $priceProductScheduleDisabler;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Resolver\PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleDisablerInterface $priceProductScheduleDisabler
     */
    public function __construct(
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleApplierByProductTypeResolverInterface $priceProductScheduleApplierByProductTypeResolver,
        PriceProductScheduleDisablerInterface $priceProductScheduleDisabler
    ) {
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleApplierByProductTypeResolver = $priceProductScheduleApplierByProductTypeResolver;
        $this->priceProductScheduleDisabler = $priceProductScheduleDisabler;
    }

    /**
     * @param int $idPriceProductSchedule
     *
     * @return void
     */
    public function removeAndApplyPriceProductSchedule(int $idPriceProductSchedule): void
    {
        $priceProductScheduleTransfer = $this->priceProductScheduleRepository
            ->findPriceProductScheduleById($idPriceProductSchedule);

        if ($priceProductScheduleTransfer === null) {
            return;
        }

        $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleTransfer): void {
            $this->executeRemoveLogicTransaction($priceProductScheduleTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return void
     */
    protected function executeRemoveLogicTransaction(PriceProductScheduleTransfer $priceProductScheduleTransfer): void
    {
        $priceProductScheduleTransfer->requireIdPriceProductSchedule();
        $priceProductScheduleTransfer = $this->priceProductScheduleDisabler
            ->disablePriceProductSchedule($priceProductScheduleTransfer);

        $this->priceProductScheduleApplierByProductTypeResolver
            ->applyPriceProductScheduleByProductType($priceProductScheduleTransfer);
        $this->priceProductScheduleEntityManager
            ->deletePriceProductScheduleById($priceProductScheduleTransfer->getIdPriceProductSchedule());
    }
}
