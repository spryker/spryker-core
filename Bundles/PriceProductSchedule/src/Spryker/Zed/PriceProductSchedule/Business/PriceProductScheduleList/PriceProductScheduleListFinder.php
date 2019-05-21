<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList;

use Generated\Shared\Transfer\PriceProductScheduleListErrorTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListRequestTransfer;
use Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer;
use Spryker\Zed\PriceProductSchedule\Persistence\PriceProductScheduleRepositoryInterface;

class PriceProductScheduleListFinder implements PriceProductScheduleListFinderInterface
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
     * @param \Generated\Shared\Transfer\PriceProductScheduleListRequestTransfer $priceProductScheduleListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListResponseTransfer
     */
    public function findPriceProductScheduleList(
        PriceProductScheduleListRequestTransfer $priceProductScheduleListRequestTransfer
    ): PriceProductScheduleListResponseTransfer {
        $priceProductScheduleListResponseTransfer = (new PriceProductScheduleListResponseTransfer())
            ->setIsSuccess(false);

        $priceProductScheduleListTransfer = $this->priceProductScheduleRepository->findPriceProductScheduleListById($priceProductScheduleListRequestTransfer);

        if ($priceProductScheduleListTransfer === null) {
            $error = (new PriceProductScheduleListErrorTransfer())
                ->setMessage(
                    sprintf(
                        'Price Product Schedule List want\'t found by given id: %s',
                        $priceProductScheduleListRequestTransfer->getIdPriceProductScheduleList()
                    )
                );
            $priceProductScheduleListResponseTransfer->addError($error);

            return $priceProductScheduleListResponseTransfer;
        }

        return $priceProductScheduleListResponseTransfer
            ->setIsSuccess(true)
            ->setPriceProductScheduleList($priceProductScheduleListTransfer);
    }
}
