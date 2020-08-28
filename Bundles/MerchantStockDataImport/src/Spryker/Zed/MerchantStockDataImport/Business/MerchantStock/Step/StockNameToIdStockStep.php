<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\Step;

use Orm\Zed\Stock\Persistence\Map\SpyStockTableMap;
use Orm\Zed\Stock\Persistence\SpyStockQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantStockDataImport\Business\MerchantStock\DataSet\MerchantStockDataSetInterface;

class StockNameToIdStockStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idStockCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $stockName = $dataSet[MerchantStockDataSetInterface::STOCK_NAME];

        if (!$stockName) {
            throw new InvalidDataException(sprintf('"%s" is required.', MerchantStockDataSetInterface::STOCK_NAME));
        }

        if (!isset($this->idStockCache[$stockName])) {
            $this->idStockCache[$stockName] = $this->getIdStock($stockName);
        }

        $dataSet[MerchantStockDataSetInterface::STOCK_ID] = $this->idStockCache[$stockName];
    }

    /**
     * @param string $stockName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdStock(string $stockName): int
    {
        /** @var \Orm\Zed\Stock\Persistence\SpyStockQuery $stockQuery */
        $stockQuery = SpyStockQuery::create()->select(SpyStockTableMap::COL_ID_STOCK);

        /** @var int $idStock */
        $idStock = $stockQuery->findOneByName($stockName);

        if (!$idStock) {
            throw new EntityNotFoundException(sprintf('Could not find Stock by name "%s"', $stockName));
        }

        return $idStock;
    }
}
