<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProduct\Business\Model\PriceType;

use Orm\Zed\PriceProduct\Persistence\Map\SpyPriceTypeTableMap;
use Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface;

class PriceTypeWriter implements PriceTypeWriterInterface
{
    /**
     * @var \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface
     */
    protected $priceProductQueryContainer;

    /**
     * @param \Spryker\Zed\PriceProduct\Persistence\PriceProductQueryContainerInterface $priceProductQueryContainer
     */
    public function __construct(PriceProductQueryContainerInterface $priceProductQueryContainer)
    {
        $this->priceProductQueryContainer = $priceProductQueryContainer;
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function createPriceType($name)
    {
        $priceTypeEntity = $this->priceProductQueryContainer
            ->queryPriceType($name)
            ->findOneOrCreate();

        $priceTypeEntity
            ->setName($name)
            ->setPriceModeConfiguration(SpyPriceTypeTableMap::COL_PRICE_MODE_CONFIGURATION_BOTH)
            ->save();

        return $priceTypeEntity->getIdPriceType();
    }
}
