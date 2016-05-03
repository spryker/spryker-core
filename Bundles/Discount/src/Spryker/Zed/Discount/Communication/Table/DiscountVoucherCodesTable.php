<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Discount\Communication\Table;

use Generated\Shared\Transfer\DataTablesTransfer;
use Orm\Zed\Discount\Persistence\Map\SpyDiscountVoucherTableMap;
use Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountVoucherCodesTable extends AbstractTable
{

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
     * @param \Generated\Shared\Transfer\DataTablesTransfer $dataTablesTransfer
     * @param \Spryker\Zed\Discount\Persistence\DiscountQueryContainerInterface $discountQueryContainer
     * @param int $idPool
     * @param int|null $batchValue
     */
    public function __construct(DataTablesTransfer $dataTablesTransfer, DiscountQueryContainerInterface $discountQueryContainer, $idPool, $batchValue = null)
    {
        $this->dataTablesTransfer = $dataTablesTransfer;
        $this->discountQueryContainer = $discountQueryContainer;
        $this->idPool = $idPool;
        $this->batchValue = $batchValue;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        //FIXME: Use Url class
        $config->setUrl('table?id-pool=' . $this->idPool . '&batch=' . $this->batchValue);
        $this->tableClass = 'table-data-codes';

        $config->setHeader([
            SpyDiscountVoucherTableMap::COL_CODE => 'Voucher Code',
            SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES => 'Used',
            SpyDiscountVoucherTableMap::COL_CREATED_AT => 'Created At',
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH => 'Batch Value',
        ]);

        $config->setSortable([
            SpyDiscountVoucherTableMap::COL_CODE,
            SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES,
            SpyDiscountVoucherTableMap::COL_CREATED_AT,
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH,
        ]);

        $config->setFooterFromHeader();

        $config->setSearchable(
            array_keys($config->getHeader())
        );

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

        if ($this->batchValue !== '') {
            $generatedVoucherCodesQuery->filterByVoucherBatch($this->batchValue);
        }

        $collectionObject = $this->runQuery($generatedVoucherCodesQuery, $config, true);

        $result = [];

        /** @var \Orm\Zed\Discount\Persistence\SpyDiscountVoucher $code */
        foreach ($collectionObject as $code) {
            $result[] = [
                SpyDiscountVoucherTableMap::COL_CODE => $code->getCode(),
                SpyDiscountVoucherTableMap::COL_NUMBER_OF_USES => (int)$code->getNumberOfUses(),
                SpyDiscountVoucherTableMap::COL_CREATED_AT => $code->getCreatedAt('Y-m-d'),
                SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH => $code->getVoucherBatch(),
            ];
        }

        return $result;
    }

}
