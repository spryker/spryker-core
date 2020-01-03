<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduleListErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;
use Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig;

class PriceProductScheduleListFinder implements PriceProductScheduleListFinderInterface
{
    protected const ERROR_MESSAGE_PRICE_PRODUCT_SCHEDULE_LIST_NOT_FOUND = 'Price product schedule list was not found by provided id %s';

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface
     */
    protected $priceProductScheduleRepository;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig
     */
    protected $priceProductScheduleConfig;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface $priceProductScheduleRepository
     * @param \Spryker\Zed\PriceProductSchedule\PriceProductScheduleConfig $priceProductScheduleConfig
     */
    public function __construct(
        PriceProductScheduleRepositoryInterface $priceProductScheduleRepository,
        PriceProductScheduleConfig $priceProductScheduleConfig
    ) {
        $this->priceProductScheduleRepository = $priceProductScheduleRepository;
        $this->priceProductScheduleConfig = $priceProductScheduleConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $requestedPriceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function findPriceProductScheduleList(
        PriceProductScheduleListTransfer $requestedPriceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        $priceProductScheduleListResponseTransfer = (new PriceProductScheduleListResponseTransfer())
            ->setIsSuccess(false);

        $priceProductScheduleListTransfer = $this->priceProductScheduleRepository
            ->findPriceProductScheduleListById($requestedPriceProductScheduleListTransfer);

        if ($priceProductScheduleListTransfer === null) {
            $error = $this->createPriceProductScheduleListErrorTransfer(
                sprintf(
                    static::ERROR_MESSAGE_PRICE_PRODUCT_SCHEDULE_LIST_NOT_FOUND,
                    $requestedPriceProductScheduleListTransfer->getIdPriceProductScheduleList()
                )
            );

            return $priceProductScheduleListResponseTransfer->addError($error);
        }

        return $priceProductScheduleListResponseTransfer
            ->setIsSuccess(true)
            ->setPriceProductScheduleList($priceProductScheduleListTransfer);
    }

    /**
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findDefaultPriceProductScheduleList(): ?PriceProductScheduleListTransfer
    {
        return $this->priceProductScheduleRepository
            ->findPriceProductScheduleListByName(
                $this->priceProductScheduleConfig->getPriceProductScheduleListDefaultName()
            );
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListErrorTransfer
     */
    protected function createPriceProductScheduleListErrorTransfer(string $message): PriceProductScheduleListErrorTransfer
    {
        return (new PriceProductScheduleListErrorTransfer())->setMessage($message);
    }
}
