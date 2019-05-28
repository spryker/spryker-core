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
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;
use Throwable;

class PriceProductScheduleValidator implements PriceProductScheduleValidatorInterface
{
    protected const ERROR_MESSAGE_GROSS_AND_NET_VALUE = 'Gross and Net Amount must be integer.';
    protected const ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO = 'Dates must be in right format and to date must be greater than from.';
    protected const ERROR_MESSAGE_SCHEDULED_PRICE_ALREADY_EXISTS = 'Scheduled price already exists.';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportMapperInterface
     */
    protected $priceProductScheduleImportMapper;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
     */
    public function __construct(
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleImportMapperInterface $priceProductScheduleImportMapper
    ) {
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->priceProductScheduleImportMapper = $priceProductScheduleImportMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListImportErrorTransfer|null
     */
    public function validatePriceProductScheduleImportTransfer(
        PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
    ): ?PriceProductScheduleListImportErrorTransfer {
        if ($this->isPricesValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_GROSS_AND_NET_VALUE
            );
        }

        if ($this->isDatesValid($priceProductScheduleImportTransfer) === false) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_ACTIVE_FROM_AND_ACTIVE_TO
            );
        }

        $priceProductScheduleCriteriaFilterTransfer = $this->priceProductScheduleImportMapper
            ->mapPriceProductScheduleImportTransferToPriceProductScheduleCriteriaFilterTransfer(
                $priceProductScheduleImportTransfer,
                new PriceProductScheduleCriteriaFilterTransfer()
            );

        $priceProductScheduleTransferCount = $this->priceProductScheduleRepository->findCountPriceProductScheduleByCriteriaFilter(
            $priceProductScheduleCriteriaFilterTransfer
        );

        if ($priceProductScheduleTransferCount > 0) {
            return $this->createPriceProductScheduleListImportErrorTransfer(
                $priceProductScheduleImportTransfer,
                static::ERROR_MESSAGE_SCHEDULED_PRICE_ALREADY_EXISTS
            );
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer
     *
     * @return bool
     */
    protected function isPricesValid(PriceProductScheduleImportTransfer $priceProductScheduleImportTransfer): bool
    {
        return is_numeric($priceProductScheduleImportTransfer->getGrossAmount())
            && is_numeric($priceProductScheduleImportTransfer->getNetAmount())
            && !is_float($priceProductScheduleImportTransfer->getGrossAmount())
            && !is_float($priceProductScheduleImportTransfer->getNetAmount());
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
