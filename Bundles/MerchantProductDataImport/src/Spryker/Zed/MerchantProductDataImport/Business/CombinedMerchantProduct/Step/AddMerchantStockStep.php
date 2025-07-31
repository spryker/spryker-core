<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class AddMerchantStockStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_MERCHANT_STOCKS = 'MERCHANT_STOCKS';

    /**
     * @var array<string, int>
     */
    protected array $resolved = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $dataSet[static::KEY_MERCHANT_STOCKS] = $this->getMerchantStocks($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, int>
     */
    protected function getMerchantStocks(DataSetInterface $dataSet): array
    {
        if ($this->resolved) {
            return $this->resolved;
        }

        $spyMerchantStockEntities = SpyMerchantStockQuery::create()
            ->joinWithSpyStock()
            ->filterByFkMerchant($this->getMerchantId($dataSet))
            ->find();

        foreach ($spyMerchantStockEntities as $spyMerchantStockEntity) {
            /** @phpstan-var string $name */
            $name = $spyMerchantStockEntity->getSpyStock()->getName();

            /** @phpstan-var int $idStock */
            $idStock = $spyMerchantStockEntity->getFkStock();

            $this->resolved[$name] = $idStock;
        }

        return $this->resolved;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return int
     */
    protected function getMerchantId(DataSetInterface $dataSet): int
    {
        return $dataSet[AddMerchantIdKeyStep::KEY_ID_MERCHANT];
    }
}
