<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Business\PriceProductSchedule\Expander;

use Generated\Shared\Transfer\PriceProductScheduleTransfer;
use Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface;

class PriceProductScheduleExpander implements PriceProductScheduleExpanderInterface
{
    /**
     * @var \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface
     */
    protected $priceProductScheduleListFinder;

    /**
     * @param \Spryker\Zed\PriceProductSchedule\Business\PriceProductScheduleList\PriceProductScheduleListFinderInterface $priceProductScheduleListFinder
     */
    public function __construct(PriceProductScheduleListFinderInterface $priceProductScheduleListFinder)
    {
        $this->priceProductScheduleListFinder = $priceProductScheduleListFinder;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleTransfer $priceProductScheduleTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleTransfer
     */
    public function expandPriceProductScheduleTransferWithPriceProductScheduleList(
        PriceProductScheduleTransfer $priceProductScheduleTransfer
    ): PriceProductScheduleTransfer {
        $priceProductScheduleListTransfer = $this->priceProductScheduleListFinder
            ->findDefaultPriceProductScheduleList();

        return $priceProductScheduleTransfer->setPriceProductScheduleList($priceProductScheduleListTransfer);
    }
}
