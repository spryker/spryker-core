<?php
/**
 * Created by PhpStorm.
 * User: mykhailokozii
 * Date: 4/12/17
 * Time: 15:06
 */

namespace Spryker\Zed\AvailabilityDataFeed\Dependency\QueryContainer;

interface AvailabilityDataFeedToAvailabilityInterface
{
    /**
     * @param int $idLocale
     *
     * @return \Orm\Zed\Availability\Persistence\SpyAvailabilityAbstractQuery|\Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    public function queryAvailabilityWithStockByIdLocale($idLocale);
}