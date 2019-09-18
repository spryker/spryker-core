<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\Remover;

use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Remover\PriceProductScheduleRemoverInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleListRemover implements PriceProductScheduleListRemoverInterface
{
    use TransactionTrait;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Remover\PriceProductScheduleRemoverInterface
     */
    protected $priceProductScheduleRemover;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface
     */
    protected $priceProductScheduleListFinder;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Remover\PriceProductScheduleRemoverInterface $priceProductScheduleRemover
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface $priceProductScheduleListFinder
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     */
    public function __construct(
        PriceProductScheduleRemoverInterface $priceProductScheduleRemover,
        PriceProductScheduleListFinderInterface $priceProductScheduleListFinder,
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
    ) {
        $this->priceProductScheduleRemover = $priceProductScheduleRemover;
        $this->priceProductScheduleListFinder = $priceProductScheduleListFinder;
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function removePriceProductScheduleList(int $idPriceProductScheduleList): PriceProductScheduleListResponseTransfer
    {
        $priceProductScheduleListResponseTransfer = $this->priceProductScheduleListFinder
            ->findPriceProductScheduleList($this->createPriceProductScheduleListTransfer($idPriceProductScheduleList));

        if (!$priceProductScheduleListResponseTransfer->getIsSuccess()) {
            return $priceProductScheduleListResponseTransfer;
        }

        $priceProductScheduleListTransfer = $priceProductScheduleListResponseTransfer->requirePriceProductScheduleList()
            ->getPriceProductScheduleList();

        $this->getTransactionHandler()->handleTransaction(function () use ($priceProductScheduleListTransfer) {
            $this->executeRemoveScheduleListTransactionLogic($priceProductScheduleListTransfer);
        });

        return $priceProductScheduleListResponseTransfer
            ->setIsSuccess(true)
            ->setPriceProductScheduleList();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return void
     */
    protected function executeRemoveScheduleListTransactionLogic(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): void {
        $idPriceProductScheduleList = $priceProductScheduleListTransfer->getIdPriceProductScheduleList();
        $priceProductScheduleListTransfer->setIsActive(false);
        $this->priceProductScheduleEntityManager
            ->updatePriceProductScheduleList($priceProductScheduleListTransfer);

        $priceProductScheduleCollection = $this->priceProductScheduleRepository
            ->findPriceProductSchedulesByIdPriceProductScheduleList($idPriceProductScheduleList);

        $this->removePriceProductScheduleCollection($priceProductScheduleCollection);
        $this->priceProductScheduleEntityManager
            ->deletePriceProductScheduleListById($idPriceProductScheduleList);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer[] $priceProductScheduleCollection
     *
     * @return void
     */
    protected function removePriceProductScheduleCollection(array $priceProductScheduleCollection): void
    {
        foreach ($priceProductScheduleCollection as $priceProductScheduleTransfer) {
            $idPriceProductSchedule = $priceProductScheduleTransfer->requireIdPriceProductSchedule()
                ->getIdPriceProductSchedule();

            if ($priceProductScheduleTransfer->getIsCurrent()) {
                $this->priceProductScheduleRemover->removeAndApplyPriceProductSchedule($idPriceProductSchedule);

                return;
            }

            $this->priceProductScheduleEntityManager
                ->deletePriceProductScheduleById($idPriceProductSchedule);
        }
    }

    /**
     * @param int $idPriceProductScheduleList
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer
     */
    protected function createPriceProductScheduleListTransfer(int $idPriceProductScheduleList): PriceProductScheduleListTransfer
    {
        return (new PriceProductScheduleListTransfer())
            ->setIdPriceProductScheduleList($idPriceProductScheduleList);
    }
}
