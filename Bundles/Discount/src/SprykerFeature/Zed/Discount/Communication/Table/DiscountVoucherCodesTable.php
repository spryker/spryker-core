<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use Generated\Shared\Transfer\DataTablesTransfer;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucher;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountVoucherCodesTable extends AbstractTable
{

    /**
     * @var SpyDiscountQuery
     */
    protected $discountQueryContainer;

    /**
     * @var DataTablesTransfer
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
     * @param DiscountQueryContainer $discountQueryContainer
     * @param int $idPool
     * @param int $batchValue
     */
    public function __construct(DiscountQueryContainer $discountQueryContainer, DataTablesTransfer $dataTablesTransfer, $idPool, $batchValue = null)
    {
        $this->discountQueryContainer = $discountQueryContainer;
        $this->dataTablesTransfer = $dataTablesTransfer;
        $this->idPool = $idPool;
        $this->batchValue = $batchValue;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setUrl('table/?id-pool=' . $this->idPool . '&batch=' . $this->batchValue);
        $this->tableClass = 'table-data-codes';

        $config->setHeader([
            SpyDiscountVoucherTableMap::COL_CODE => 'Voucher Code',
            SpyDiscountVoucherTableMap::COL_CREATED_AT => 'Created At',
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH => 'Batch Value',
        ]);

        $config->setFooterFromHeader();

        $config->setSearchable([
            SpyDiscountVoucherTableMap::COL_CODE,
            SpyDiscountVoucherTableMap::COL_CREATED_AT,
            SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH,
        ]);

        return $config;
    }

    /**
     * @param TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {

        $generatedVoucherCodesQuery = $this->discountQueryContainer
            ->queryDiscountVoucher()
            ->filterByFkDiscountVoucherPool($this->idPool)
        ;

//        dump($this->dataTablesTransfer);die;

        if ('' !== $this->batchValue) {
            $generatedVoucherCodesQuery->filterByVoucherBatch($this->batchValue);
        }

        $collectionObject = $this->runQuery($generatedVoucherCodesQuery, $config, true);

        $result = [];

        /** @var SpyDiscountVoucher $code */
        foreach ($collectionObject as $code) {
//            dump($code);die;
            $result[] = [
                SpyDiscountVoucherTableMap::COL_CODE => $code->getCode(),
                SpyDiscountVoucherTableMap::COL_CREATED_AT => $code->getCreatedAt('Y-m-d'),
                SpyDiscountVoucherTableMap::COL_VOUCHER_BATCH => $code->getVoucherBatch(),
            ];
        }

        return $result;
    }

}
