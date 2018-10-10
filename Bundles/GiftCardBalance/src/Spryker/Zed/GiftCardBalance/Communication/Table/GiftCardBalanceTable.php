<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GiftCardBalance\Communication\Table;

use DateTime;
use Orm\Zed\GiftCard\Persistence\Map\SpyGiftCardTableMap;
use Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLog;
use Orm\Zed\Sales\Persistence\Map\SpySalesOrderTableMap;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\GiftCardBalance\Dependency\Facade\GiftCardBalanceToMoneyFacadeInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class GiftCardBalanceTable extends AbstractTable
{
    public const COL_CREATE_AT = 'created_at';
    public const COL_CUSTOMER_REFERENCE = 'customer_name';
    public const COL_GIFT_CARD_NAME = 'gift_card_name';
    public const COL_ID_SALES_ORDER = 'id_sales_order';
    public const COL_BALANCE = 'balance';

    /**
     * @var \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalanceQueryContainerInterface
     */
    protected $giftCardBalanceQueryContainer;

    /**
     * @var \Spryker\Zed\GiftCardBalance\Dependency\Facade\GiftCardBalanceToMoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var int|null
     */
    protected $idGiftCard;

    /**
     * @param \Spryker\Zed\GiftCardBalance\Persistence\GiftCardBalanceQueryContainerInterface $giftCardBalanceQueryContainer
     * @param \Spryker\Zed\GiftCardBalance\Dependency\Facade\GiftCardBalanceToMoneyFacadeInterface $moneyFacade
     * @param int|null $idGiftCard
     */
    public function __construct(
        $giftCardBalanceQueryContainer,
        GiftCardBalanceToMoneyFacadeInterface $moneyFacade,
        $idGiftCard = null
    ) {
        $this->giftCardBalanceQueryContainer = $giftCardBalanceQueryContainer;
        $this->moneyFacade = $moneyFacade;
        $this->idGiftCard = $idGiftCard;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            self::COL_CREATE_AT => 'Date Time',
            self::COL_ID_SALES_ORDER => 'Order ID',
            self::COL_CUSTOMER_REFERENCE => 'Customer',
            self::COL_GIFT_CARD_NAME => 'Gift Card name',
            self::COL_BALANCE => 'Balance',
        ]);

        $config->setUrl(sprintf('table?id-gift-card=%d', $this->idGiftCard));

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->prepareQuery();

        $customersCollection = $this->runQuery($query, $config, true);

        if ($customersCollection->count() < 1) {
            return [];
        }

        return $this->formatCollection($customersCollection);
    }

    /**
     * @return \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLogQuery
     */
    protected function prepareQuery()
    {
        $query = $this->giftCardBalanceQueryContainer
            ->queryGiftCardBalanceLog()
            ->leftJoinSpyGiftCard()
            ->leftJoinSpySalesOrder()
            ->withColumn(SpyGiftCardTableMap::COL_NAME, self::COL_GIFT_CARD_NAME)
            ->withColumn(SpySalesOrderTableMap::COL_ID_SALES_ORDER, self::COL_ID_SALES_ORDER)
            ->withColumn(SpySalesOrderTableMap::COL_CREATED_AT, self::COL_CREATE_AT)
            ->withColumn(SpySalesOrderTableMap::COL_CUSTOMER_REFERENCE, self::COL_CUSTOMER_REFERENCE);

        if ($this->idGiftCard) {
            $query->useSpyGiftCardQuery()
                    ->filterByIdGiftCard($this->idGiftCard)
                ->endUse();
        }

        return $query;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $giftCardBalanceLogCollection
     *
     * @return array
     */
    protected function formatCollection(ObjectCollection $giftCardBalanceLogCollection)
    {
        $giftCardBalanceList = [];

        foreach ($giftCardBalanceLogCollection as $giftCardBalanceLogEntity) {
            $giftCardBalanceList[] = $this->hydrateRow($giftCardBalanceLogEntity);
        }

        return $giftCardBalanceList;
    }

    /**
     * @param \Orm\Zed\GiftCardBalance\Persistence\SpyGiftCardBalanceLog $giftCardBalanceLogEntity
     *
     * @return array
     */
    protected function hydrateRow(SpyGiftCardBalanceLog $giftCardBalanceLogEntity)
    {
        $giftCardBalanceLogRow = $giftCardBalanceLogEntity->toArray();

        $createdAt = DateTime::createFromFormat(
            'Y-m-d H:i:s.u',
            $giftCardBalanceLogEntity->getVirtualColumn(self::COL_CREATE_AT)
        );

        $giftCardBalanceLogRow[self::COL_CREATE_AT] = $createdAt->format('Y-m-d H:i:s');
        $giftCardBalanceLogRow[self::COL_ID_SALES_ORDER] = $giftCardBalanceLogEntity->getVirtualColumn(self::COL_ID_SALES_ORDER);
        $giftCardBalanceLogRow[self::COL_CUSTOMER_REFERENCE] = $giftCardBalanceLogEntity->getVirtualColumn(self::COL_CUSTOMER_REFERENCE);
        $giftCardBalanceLogRow[self::COL_GIFT_CARD_NAME] = $giftCardBalanceLogEntity->getVirtualColumn(self::COL_GIFT_CARD_NAME);
        $giftCardBalanceLogRow[self::COL_BALANCE] = $this->formatMoneyInt($giftCardBalanceLogEntity->getValue());

        return $giftCardBalanceLogRow;
    }

    /**
     * @param int $value
     *
     * @return string
     */
    protected function formatMoneyInt($value)
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value);
        return $this->moneyFacade->formatWithSymbol($moneyTransfer);
    }
}
