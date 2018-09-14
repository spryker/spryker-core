<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Generated\Shared\Transfer\DataTablesTransfer;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Orm\Zed\Discount\Persistence\SpyDiscountVoucher;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountVoucherCodesTable extends AbstractTable
{
    const HEADER_COL_ACTIONS = 'Actions';

    /**
     * @var \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface
     */
    protected $discountQueryContainer;

    /**
     * @var \Generated\Shared\Transfer\DataTablesTransfer
     */
    protected $dataTablesTransfer;

    /**
     * @var int
     */
    protected $idPool;

    /**
     * @var int
     */
    protected $batchValue;

    /**
     * @var int
     */
    protected $idDiscount;

    /**
     * @param \Generated\Shared\Transfer\DataTablesTransfer $dataTablesTransfer
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param int $idPool
     * @param int $idDiscount
     * @param int|null $batchValue
     */
    public function __construct(
        DataTablesTransfer $dataTablesTransfer,
        DiscountQueryContainerInterface $discountQueryContainer,
        $idPool,
        $idDiscount,
        $batchValue = null
    ) {
        $this->dataTablesTransfer = $dataTablesTransfer;
        $this->discountQueryContainer = $discountQueryContainer;
        $this->idPool = $idPool;
        $this->idDiscount = $idDiscount;
        $this->batchValue = $batchValue;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $url = Url::generate(
            'table',
            [
                'id-pool' => $this->idPool,
                'id-discount' => $this->idDiscount,
                'batch' => $this->batchValue,
            ]
        );

        $config->setUrl($url->build());

        $this->tableClass .= ' table-data-codes';

        $config->setHeader([
            SpyDiscountVoucherTableMap::COL_CODE => 'Voucher Code',
            SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES => 'Used',
            SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES => 'Max nr. of uses',
            SpyDiscountVoucherTableMap::COL_CREATED_AT => 'Created At',
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH => 'Batch Value',
            self::HEADER_COL_ACTIONS => self::HEADER_COL_ACTIONS,
        ]);

        $config->setSortable([
            SpyDiscountVoucherTableMap::COL_CODE,
            SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES,
            SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES,
            SpyDiscountVoucherTableMap::COL_CREATED_AT,
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH,
        ]);

        $config->setDefaultSortField(SpyDiscountVoucherTableMap::COL_CREATED_AT);

        $config->setSearchable([
            SpyDiscountVoucherTableMap::COL_CODE,
            SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES,
            SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES,
            SpyDiscountVoucherTableMap::COL_CREATED_AT,
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH,
        ]);

        $config->addRawColumn(self::HEADER_COL_ACTIONS);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $generatedVoucherCodesQuery = $this->discountQueryContainer
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($this->idPool);

        if ($this->batchValue) {
            $generatedVoucherCodesQuery->filterByVoucherBatch($this->batchValue);
        }

        /** @var \Orm\Zed\Discount\Persistence\SpyDiscountVoucher[] $discountVoucherEntities */
        $discountVoucherEntities = $this->runQuery($generatedVoucherCodesQuery, $config, true);

        $result = [];

        foreach ($discountVoucherEntities as $discountVoucherEntity) {
            $result[] = [
                SpyDiscountVoucherTableMap::COL_CODE => $discountVoucherEntity->getCode(),
                SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES => (int)$discountVoucherEntity->getNumberOfUses(),
                SpyDiscountVoucherTableMap::COL_MAX_NUMBER_OF_USES => (int)$discountVoucherEntity->getMaxNumberOfUses(),
                SpyDiscountVoucherTableMap::COL_CREATED_AT => $discountVoucherEntity->getCreatedAt('Y-m-d'),
                SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH => $discountVoucherEntity->getVoucherBatch(),
                self::HEADER_COL_ACTIONS => $this->buildLinks($discountVoucherEntity),
            ];
        }

        return $result;
    }

    /**
     * @param \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $discountVoucherEntity
     *
     * @return string
     */
    protected function buildLinks(SpyDiscountVoucher $discountVoucherEntity)
    {
        $buttons = [];

        $deleteVoucherCodeUrl = Url::generate(
            '/discount/voucher/delete-voucher-code',
            [
                'id-discount' => $this->idDiscount,
                'id-voucher' => $discountVoucherEntity->getIdDiscountVoucher(),
            ]
        )->build();

        $buttons[] = $this->generateRemoveButton($deleteVoucherCodeUrl, 'Delete');

        return implode(' ', $buttons);
    }
}
