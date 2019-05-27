<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Business\Exception\PriceProductScheduleListImportException;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleValidatorInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductScheduleListImporter implements PriceProductScheduleListImporterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleValidatorInterface
     */
    protected $priceProductScheduleValidator;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface
     */
    protected $priceProductScheduleMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleValidatorInterface $priceProductScheduleValidator
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleValidatorInterface $priceProductScheduleValidator,
        PriceProductScheduleMapperInterface $priceProductScheduleMapper
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleValidator = $priceProductScheduleValidator;
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
            ->setIsSuccess(false);

        foreach ($priceProductScheduledListImportRequest->getItems() as $priceProductScheduleImportTransfer) {
            try {
                $priceProductScheduledListImportResponse = $this->priceProductScheduleValidator->validatePriceProductScheduleImportTransfer(
                    $priceProductScheduleImportTransfer,
                    $priceProductScheduledListImportResponse
                );

                if ($priceProductScheduledListImportResponse->getErrors()->count() > 0) {
                    continue;
                }

                $this->savePriceProductSchedule(
                    $priceProductScheduleImportTransfer,
                    $priceProductScheduledListImportRequest->getPriceProductScheduleList()
                );

                $priceProductScheduledListImportResponse->setIsSuccess(true);
            } catch (PriceProductScheduleListImportException $e) {
                $priceProductScheduledListImportResponse->addError(
                    $this->createPriceProductScheduleListImportErrorTransfer(
                        $priceProductScheduleImportTransfer,
                        $e->getMessage()
                    )
                );
            }
        }

        return $priceProductScheduledListImportResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param string $errorMessage
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer
     */
    protected function createPriceProductScheduleListImportErrorTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        string $errorMessage
    ): PriceProductScheduleListImportErrorTransfer {
        return (new PriceProductScheduleListImportErrorTransfer())
            ->setPriceProductScheduleImport($priceProductScheduleImportTransfer)
            ->setMessage($errorMessage);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return void
     */
    protected function savePriceProductSchedule(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): void {
        $priceProductScheduleTransfer = $this->priceProductScheduleMapper
            ->mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
                $priceProductScheduleImportTransfer,
                new PriceProductScheduleTransfer()
            );

        $priceProductScheduleTransfer->setPriceProductScheduleList(
            $priceProductScheduleListTransfer
        );

        $this->priceProductScheduleEntityManager->savePriceProductSchedule($priceProductScheduleTransfer);
    }
}
