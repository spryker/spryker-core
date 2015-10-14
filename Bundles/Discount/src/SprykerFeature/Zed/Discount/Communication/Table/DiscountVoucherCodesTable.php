<?php

namespace SprykerFeature\Zed\Discount\Communication\Table;

use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerFeature\Zed\Discount\Persistence\Propel\Map\SpyDiscountVoucherTableMap;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountQuery;
use SprykerFeature\Zed\Gui\Communication\Table\AbstractTable;
use SprykerFeature\Zed\Gui\Communication\Table\TableConfiguration;

class DiscountVoucherCodesTable extends AbstractTable
{

    /**
     * @var SpyDiscountQuery
     */
    protected $discountQueryContainer;

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
    public function __construct(DiscountQueryContainer $discountQueryContainer, $idPool, $batchValue = null)
    {
        $this->discountQueryContainer = $discountQueryContainer;
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

        $config->setHeader([
            SpyDiscountVoucherTableMap::COL_CODE => 'Voucher Code',
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

        if ('' !== $this->batchValue) {
            $generatedVoucherCodesQuery->filterByVoucherBatch($this->batchValue);
        }

        $collectionObject = $this->runQuery($generatedVoucherCodesQuery, $config, true);

        $result = [];
        foreach ($collectionObject as $code) {
            $result[] = [
                SpyDiscountVoucherTableMap::COL_CODE => $code->getCode(),
            ];
        }

        return $result;
    }

}
