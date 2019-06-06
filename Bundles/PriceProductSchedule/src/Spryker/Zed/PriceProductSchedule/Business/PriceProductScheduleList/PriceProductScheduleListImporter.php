<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduledListImportRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportValidatorInterface;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductScheduleListImporter implements PriceProductScheduleListImporterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportValidatorInterface
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
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportValidatorInterface $priceProductScheduleValidator
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleMapperInterface $priceProductScheduleMapper
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\DataExpander\PriceProductTransferDataExpanderInterface[] $dataExpanderList
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager,
        PriceProductScheduleImportValidatorInterface $priceProductScheduleValidator,
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
        $priceProductScheduledListImportResponse = $this->createPriceProductScheduleListImportResponseTransfer(
            $priceProductScheduledListImportRequest->getPriceProductScheduleList()
        );

        foreach ($priceProductScheduledListImportRequest->getItems() as $priceProductScheduleImportTransfer) {
            $priceProductScheduleListImportError = $this->priceProductScheduleValidator
                ->validatePriceProductScheduleImportTransfer($priceProductScheduleImportTransfer);

            if ($priceProductScheduleListImportError !== null) {
                $priceProductScheduledListImportResponse->addError($priceProductScheduleListImportError);

                continue;
            }

            $priceProductScheduleTransfer = $this->priceProductScheduleMapper
                ->mapPriceProductScheduleImportTransferToPriceProductScheduleTransfer(
                    $priceProductScheduleImportTransfer,
                    new PriceProductScheduleTransfer()
                );

            $priceProductScheduleTransfer = $this->expandPriceProductTransfer($priceProductScheduleTransfer);

            $priceProductScheduleTransfer
                ->setPriceProductScheduleList($priceProductScheduledListImportRequest->getPriceProductScheduleList());

            $this->priceProductScheduleEntityManager->savePriceProductSchedule($priceProductScheduleTransfer);

            $priceProductScheduledListImportResponse
                ->setIsSuccess(true);
        }

        return $priceProductScheduledListImportResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    protected function expandPriceProductTransfer(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductTransfer = $priceProductScheduleTransfer->getPriceProduct();

        foreach ($this->dataExpanderList as $dataExpander) {
            $priceProductTransfer = $dataExpander->expand($priceProductTransfer);
        }

        return $priceProductScheduleTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    protected function createPriceProductScheduleListImportResponseTransfer(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListImportResponseTransfer {
        return (new PriceProductScheduleListImportResponseTransfer())
            ->setPriceProductScheduleList($priceProductScheduleListTransfer)
            ->setIsSuccess(false);
    }
}
