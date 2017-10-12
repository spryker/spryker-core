<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Persistence;

use Generated\Shared\Transfer\AvailabilityDataFeedTransfer;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface AvailabilityDataFeedQueryContainerInterface extends QueryContainerInterface
{
    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\AvailabilityDataFeedTransfer $availabilityDataFeedTransfer
     *
     * @return \Spryker\Zed\Stock\Persistence\StockQueryContainerInterface
     */
    public function queryAvailabilityDataFeed(AvailabilityDataFeedTransfer $availabilityDataFeedTransfer);
}
