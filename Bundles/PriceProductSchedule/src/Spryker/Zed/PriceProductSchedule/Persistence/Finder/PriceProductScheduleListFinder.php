<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductSchedule\Persistence\Finder;

use Generated\Shared\Transfer\PriceProductScheduleListTransfer;
use Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery;
use Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface;

class PriceProductScheduleListFinder implements PriceProductScheduleListFinderInterface
{
    protected const ALIAS_NUMBER_OF_PRICES = 'numberOfPrices';
    protected const ALIAS_NUMBER_OF_PRODUCTS = 'numberOfProducts';

    protected const EXPRESSION_NUMBER_OF_PRICES = 'COUNT(%s)';
    protected const EXPRESSION_NUMBER_OF_PRODUCTS = 'COUNT(DISTINCT %s) + COUNT(DISTINCT %s)';

    protected const COL_ID_PRICE_PRODUCT_SCHEDULE = 'id_price_product_schedule';
    protected const COL_FK_PRODUCT = 'fk_product';
    protected const COL_FK_PRODUCT_ABSTRACT = 'fk_product_abstract';
    /**
     * @var \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery
     */
    protected $priceProductScheduleListQuery;

    /**
     * @var \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface
     */
    protected $priceProductScheduleListMapper;

    /**
     * @param \Orm\Zed\PriceProductSchedule\Persistence\SpyPriceProductScheduleListQuery $priceProductScheduleListQuery
     * @param \Spryker\Zed\PriceProductSchedule\Persistence\Propel\Mapper\PriceProductScheduleListMapperInterface $priceProductScheduleListMapper
     */
    public function __construct(
        SpyPriceProductScheduleListQuery $priceProductScheduleListQuery,
        PriceProductScheduleListMapperInterface $priceProductScheduleListMapper
    ) {
        $this->priceProductScheduleListQuery = $priceProductScheduleListQuery;
        $this->priceProductScheduleListMapper = $priceProductScheduleListMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductScheduleListTransfer $priceProductScheduleListTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListById(
        PriceProductScheduleListTransfer $priceProductScheduleListTransfer
    ): ?PriceProductScheduleListTransfer {
        $priceProductScheduleListEntity = $this->priceProductScheduleListQuery
            ->filterByIdPriceProductScheduleList($priceProductScheduleListTransfer->getIdPriceProductScheduleList())
            ->usePriceProductScheduleQuery()
            ->addAsColumn(
                static::ALIAS_NUMBER_OF_PRICES,
                sprintf(
                    static::EXPRESSION_NUMBER_OF_PRICES,
                    static::COL_ID_PRICE_PRODUCT_SCHEDULE
                )
            )
            ->addAsColumn(
                static::ALIAS_NUMBER_OF_PRODUCTS,
                sprintf(
                    static::EXPRESSION_NUMBER_OF_PRODUCTS,
                    static::COL_FK_PRODUCT,
                    static::COL_FK_PRODUCT_ABSTRACT
                )
            )
            ->endUse()
            ->groupByIdPriceProductScheduleList()
            ->findOne();

        if ($priceProductScheduleListEntity === null) {
            return null;
        }

        return $this->priceProductScheduleListMapper
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
                $priceProductScheduleListEntity,
                new PriceProductScheduleListTransfer()
            );
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\PriceProductScheduleListTransfer|null
     */
    public function findPriceProductScheduleListByName(string $name): ?PriceProductScheduleListTransfer
    {
        $priceProductScheduleListEntity = $this->priceProductScheduleListQuery
            ->filterByName($name)
            ->findOne();

        if ($priceProductScheduleListEntity === null) {
            return null;
        }

        return $this->priceProductScheduleListMapper
            ->mapPriceProductScheduleListEntityToPriceProductScheduleListTransfer(
                $priceProductScheduleListEntity,
                new PriceProductScheduleListTransfer()
            );
    }
}
