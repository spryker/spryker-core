<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule;

use DateTime;
use Generated\Shared\Transfer\PriceProductScheduleCriteriaFilterTransfer;
use Generated\Shared\Transfer\PriceProductScheduleImportTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer;
use Propel\Runtime\Exception\PropelException;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;
use Throwable;

class PriceProductScheduleValidator implements PriceProductScheduleValidatorInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     */
    public function __construct(
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
    ) {
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     * @param \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer $priceProductScheduledListImportResponse
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportResponseTransfer
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer,
        PriceProductScheduleListImportResponseTransfer $priceProductScheduledListImportResponse
    ): PriceProductScheduleListImportResponseTransfer {
        if ($this->isPricesValid($priceProductScheduleImportTransfer) === false) {
            $priceProductScheduledListImportResponse
                ->addError(
                    $this->createPriceProductScheduleListImportErrorTransfer(
                        $priceProductScheduleImportTransfer,
                        'Gross and Net Amount must be integer.'
                    )
                );
        }

        if ($this->isDatesValid($priceProductScheduleImportTransfer) === false) {
            $priceProductScheduledListImportResponse->addError(
                $this->createPriceProductScheduleListImportErrorTransfer(
                    $priceProductScheduleImportTransfer,
                    'Dates must be in right format and "to" date must be greater than "from".'
                )
            );
        }

        $priceProductScheduleCriteriaFilterTransfer = $this->preparePriceProductScheduleByCriteriaFilter(
            $priceProductScheduleImportTransfer
        );

        try {
            $priceProductScheduleTransferCount = $this->priceProductScheduleRepository->findCountPriceProductScheduleByCriteriaFilter(
                $priceProductScheduleCriteriaFilterTransfer
            );

            if ($priceProductScheduleTransferCount > 0) {
                $priceProductScheduledListImportResponse->addError(
                    $this->createPriceProductScheduleListImportErrorTransfer(
                        $priceProductScheduleImportTransfer,
                        'Scheduled price already exists.'
                    )
                );
            }
        } catch (PropelException $exception) {
            $priceProductScheduledListImportResponse->addError(
                $this->createPriceProductScheduleListImportErrorTransfer(
                    $priceProductScheduleImportTransfer,
                    'Some error happened during insert into the database. Please make sure that data is valid.'
                )
            );
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
            ->fromArray($priceProductScheduleImportTransfer->toArray(), true);
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isPricesValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        return is_int($priceProductScheduleImportTransfer->getGrossAmount())
            && is_int($priceProductScheduleImportTransfer->getNetAmount());
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isDatesValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        try {
            $activeFrom = new DateTime($priceProductScheduleImportTransfer->getActiveFrom());
            $activeTo = new DateTime($priceProductScheduleImportTransfer->getActiveTo());

            return $activeTo > $activeFrom;
        } catch (Throwable $e) {
            return false;
        }
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
}
