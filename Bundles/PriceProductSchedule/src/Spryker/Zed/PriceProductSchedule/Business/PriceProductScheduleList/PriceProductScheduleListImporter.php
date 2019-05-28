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
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
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
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander\PriceProductTransferDataExpanderInterface[]
     */
    protected $dataExpanderList;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleValidatorInterface $priceProductScheduleValidator
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander\PriceProductTransferDataExpanderInterface[] $dataExpanderList
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleValidatorInterface $priceProductScheduleValidator,
        PriceProductScheduleMapperInterface $priceProductScheduleMapper,
        array $dataExpanderList
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
        $this->priceProductScheduleValidator = $priceProductScheduleValidator;
        $this->priceProductScheduleMapper = $priceProductScheduleMapper;
        $this->dataExpanderList = $dataExpanderList;
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
            ->setPriceProductScheduleList($priceProductScheduledListImportRequest->getPriceProductScheduleList())
            ->setIsSuccess(false);

        foreach ($priceProductScheduledListImportRequest->getItems() as $priceProductScheduleImportTransfer) {
            $priceProductScheduleListImportError = $this->priceProductScheduleValidator->validatePriceProductScheduleImportTransfer(
                $priceProductScheduleImportTransfer
            );

            if ($priceProductScheduleListImportError !== null) {
                $priceProductScheduledListImportResponse->addError($priceProductScheduleListImportError);

                continue;
            }

            $priceProductScheduleTransfer = $this->priceProductScheduleMapper
                ->mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
                    $priceProductScheduleImportTransfer,
                    new PriceProductScheduleTransfer()
                );

            $this->expandPriceProductTransfer(
                $priceProductScheduleTransfer,
                $priceProductScheduleImportTransfer,
                $priceProductScheduledListImportResponse
            );

            if ($priceProductScheduledListImportResponse->getErrors()->count() > 0) {
                continue;
            }

            $priceProductScheduleTransfer
                ->setPriceProduct($priceProductScheduleTransfer->getPriceProduct())
                ->setPriceProductScheduleList($priceProductScheduledListImportRequest->getPriceProductScheduleList());

            $this->priceProductScheduleEntityManager->savePriceProductSchedule($priceProductScheduleTransfer);

            $priceProductScheduledListImportResponse
                ->setIsSuccess(true);
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
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer $priceProductScheduledListImportResponse
     *
     * @return void
     */
    protected function expandPriceProductTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer,
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleListImportResponseTransfer $priceProductScheduledListImportResponse
    ): void {
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();

        foreach ($this->dataExpanderList as $dataExpander) {
            $priceProductExpandResultTransfer = $dataExpander->expand($priceProductTransfer);

            if ($priceProductExpandResultTransfer->getIsSuccess() === false) {
                $priceProductScheduledListImportResponse
                    ->addError($priceProductExpandResultTransfer
                        ->getError()
                        ->setPriceProductScheduleImport($priceProductScheduleImportTransfer));
                break;
            }

            $priceProductTransfer = $priceProductExpandResultTransfer->getPriceProduct();
        }
    }
}
