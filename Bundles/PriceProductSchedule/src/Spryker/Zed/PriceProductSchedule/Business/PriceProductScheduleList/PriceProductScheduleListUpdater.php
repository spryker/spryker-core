<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduleListErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Spryker\Zed\PriceProductSchedule\Persistence\Exception\PriceProductScheduleListNotFoundException;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface;

class PriceProductScheduleListUpdater implements PriceProductScheduleListUpdaterInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface
     */
    protected $priceProductScheduleEntityManager;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
     */
    public function __construct(
        PriceProductScheduleEntityManagerInterface $priceProductScheduleEntityManager
    ) {
        $this->priceProductScheduleEntityManager = $priceProductScheduleEntityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function updatePriceProductScheduleList(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): PriceProductScheduleListResponseTransfer {
        $priceProductScheduleListResponseTransfer = new PriceProductScheduleListResponseTransfer();

        try {
            $priceProductScheduleListTransfer = $this->priceProductScheduleEntityManager
                ->updatePriceProductScheduleList($priceProductScheduleListTransfer);
            $priceProductScheduleListResponseTransfer
                ->setPriceProductScheduleList($priceProductScheduleListTransfer)
                ->setIsSuccess(true);
        } catch (PriceProductScheduleListNotFoundException $exception) {
            $priceProductScheduleListErrorTransfer = (new PriceProductScheduleListErrorTransfer())
                ->setMessage($exception->getMessage());
            $priceProductScheduleListResponseTransfer
                ->setIsSuccess(false)
                ->addError($priceProductScheduleListErrorTransfer);
        }

        return $priceProductScheduleListResponseTransfer;
    }
}
