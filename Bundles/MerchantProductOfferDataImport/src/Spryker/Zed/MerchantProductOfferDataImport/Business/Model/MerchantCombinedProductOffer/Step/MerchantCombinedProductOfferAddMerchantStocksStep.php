<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\Step;

use Generated\Shared\Transfer\DataImporterConfigurationContextTransfer;
use Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery;
use Spryker\Zed\DataImport\Business\Model\DataImporter;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\MerchantCombinedProductOffer\DataSet\CombinedProductOfferDataSetInterface;

class MerchantCombinedProductOfferAddMerchantStocksStep implements DataImportStepInterface
{
    /**
     * @var array<int, array<string, int>>
     */
    protected array $merchantStockCache = [];

    public function execute(DataSetInterface $dataSet): void
    {
        $context = $this->getContext($dataSet);

        $dataSet[CombinedProductOfferDataSetInterface::DATA_MERCHANT_STOCKS] = $this->getMerchantStocks(
            $context->getIdMerchantOrFail(),
        );
    }

    /**
     * @return array<string, int>
     */
    protected function getMerchantStocks(int $idMerchant): array
    {
        if (isset($this->merchantStockCache[$idMerchant])) {
            return $this->merchantStockCache[$idMerchant];
        }

        /** @var \Orm\Zed\MerchantStock\Persistence\SpyMerchantStockQuery $spyMerchantStockQuery */
        $spyMerchantStockQuery = SpyMerchantStockQuery::create();

        $merchantStockEntities = $spyMerchantStockQuery
            ->joinWithSpyStock()
            ->filterByFkMerchant($idMerchant)
            ->find();

        $merchantStocks = [];
        foreach ($merchantStockEntities as $merchantStockEntity) {
            $stockName = $merchantStockEntity->getSpyStock()->getName();
            $idStock = $merchantStockEntity->getFkStock();

            $merchantStocks[$stockName] = $idStock;
        }

        $this->merchantStockCache[$idMerchant] = $merchantStocks;

        return $merchantStocks;
    }

    protected function getContext(DataSetInterface $dataSet): DataImporterConfigurationContextTransfer
    {
        return $dataSet[DataImporter::KEY_CONTEXT];
    }
}
