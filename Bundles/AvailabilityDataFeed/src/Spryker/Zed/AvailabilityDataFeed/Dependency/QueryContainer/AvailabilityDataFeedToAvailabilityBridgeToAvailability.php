<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityDataFeed\Dependency\QueryContainer;

use Spryker\Zed\Availability\Persistence\AvailabilityQueryContainerInterface;

class AvailabilityDataFeedToAvailabilityBridgeToAvailability implements AvailabilityDataFeedToAvailabilityInterface
{

    /**
     * @var AvailabilityQueryContainerInterface
     */
    protected $availabilityQueryContainer;

    /**
     * AvailabilityDataFeedBridge constructor.
     *
     * @param AvailabilityQueryContainerInterface $availabilityQueryContainer
     */
    public function __construct($availabilityQueryContainer)
    {
        $this->availabilityQueryContainer = $availabilityQueryContainer;
    }

    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale)
    {
        return $this->availabilityQueryContainer
            ->queryAvailabilityWithStockByIdLocale($idLocale);
    }

}
