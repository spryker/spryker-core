<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleListImporter implements PriceProductScheduleListImporterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListCreatorInterface
     */
    protected $priceProductScheduleListCreator;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListCreatorInterface $priceProductScheduleListCreator
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleListCreatorInterface $priceProductScheduleListCreator,
        PriceProductScheduleMapperInterface $priceProductScheduleMapper
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->priceProductScheduleListCreator = $priceProductScheduleListCreator;
        $this->priceProductScheduleMapper = $priceProductScheduleMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    public function importPriceProductSchedules(
        PriceProductScheduledListImportRequestTransfer $priceProductScheduledListImportRequest
    ): PriceProductScheduleListImportResponseTransfer {
        $priceProductScheduledListImportResponse = (new PriceProductScheduleListImportResponseTransfer())
            ->setIsSuccess(true);
        $priceProductScheduledListResponse = $this->priceProductScheduleListCreator->createPriceProductScheduleList(
            $priceProductScheduledListImportRequest->getPriceProductScheduleList()
        );

        foreach ($priceProductScheduledListImportRequest->getItems() as $priceProductScheduleImportTransfer) {
            if (is_int($priceProductScheduleImportTransfer->getGrossAmount()) === false
                || is_int($priceProductScheduleImportTransfer->getNetAmount()) == false) {
                $priceProductScheduledListImportResponse->setIsSuccess(false);
                $priceProductScheduledListImportResponse->addError(
                    (new PriceProductScheduleListImportErrorTransfer())
                        ->setPriceProductScheduleImport($priceProductScheduleImportTransfer)
                        ->setMessage('Gross and Net Amount must be integer')
                );

                continue;
            }

            $priceProductScheduleCriteriaFilterTransfer = $this->preparePriceProductScheduleByCriteriaFilter(
                $priceProductScheduleImportTransfer
            );

            $priceProductScheduleTransferCount = $this->priceProductScheduleRepository->findCountPriceProductScheduleByCriteriaFilter(
                $priceProductScheduleCriteriaFilterTransfer
            );

            if ($priceProductScheduleTransferCount) {
                $priceProductScheduledListImportResponse->setIsSuccess(false);
                $priceProductScheduledListImportResponse->addError(
                    (new PriceProductScheduleListImportErrorTransfer())
                        ->setPriceProductScheduleImport($priceProductScheduleImportTransfer)
                        ->setMessage('Scheduled price already exists')
                );

                continue;
            }

            try {
                $priceProductScheduleTransfer = $this->priceProductScheduleMapper
                    ->mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
                        $priceProductScheduleImportTransfer,
                        new PriceProductScheduleTransfer()
                    );

                $priceProductScheduleTransfer->setPriceProductScheduleList(
                    $priceProductScheduledListResponse->getPriceProductScheduleList()
                );

                $this->priceProductScheduleEntityManager->savePriceProductSchedule($priceProductScheduleTransfer);
            } catch (PriceProductScheduleListImportException $e) {
                $priceProductScheduledListImportResponse->setIsSuccess(false);
                $priceProductScheduledListImportResponse->addError(
                    (new PriceProductScheduleListImportErrorTransfer())
                        ->setPriceProductScheduleImport($priceProductScheduleImportTransfer)
                        ->setMessage($e->getMessage())
                );

                continue;
            }
        }

        return $priceProductScheduledListImportResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer
     */
    protected function preparePriceProductScheduleByCriteriaFilter(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): PriceProductScheduleCriteriaFilterTransfer {
        return (new PriceProductScheduleCriteriaFilterTransfer())
            ->setSkuProductAbstract($priceProductScheduleImportTransfer->getSkuProductAbstract())
            ->setSkuProduct($priceProductScheduleImportTransfer->getSkuProduct())
            ->setPriceTypeName($priceProductScheduleImportTransfer->getPriceTypeName())
            ->setGrossAmount($priceProductScheduleImportTransfer->getGrossAmount())
            ->setNetAmount($priceProductScheduleImportTransfer->getNetAmount())
            ->setActiveTo($priceProductScheduleImportTransfer->getActiveTo())
            ->setActiveFrom($priceProductScheduleImportTransfer->getActiveFrom())
            ->setStoreName($priceProductScheduleImportTransfer->getStoreName())
            ->setCurrencyName($priceProductScheduleImportTransfer->getCurrencyName());
    }
}
