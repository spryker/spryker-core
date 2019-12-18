<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductOfferDataImport\Business\Model\Step;

use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductOfferDataImport\Business\Model\DataSet\MerchantProductOfferDataSetInterface;

class StoreNameToIdStoreStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idStoreCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeName = $dataSet[MerchantProductOfferDataSetInterface::STORE_NAME];

        if (!isset($this->idStoreCache[$storeName])) {
            $this->addIdStoreToCache($storeName);
        }

        $dataSet[MerchantProductOfferDataSetInterface::ID_STORE] = $this->idStoreCache[$storeName];
    }

    /**
     * @param string $storeName
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    protected function addIdStoreToCache(string $storeName): void
    {
        $idStore = SpyStoreQuery::create()
            ->select(SpyStoreTableMap::COL_ID_STORE)
            ->findOneByName($storeName);

        if (!$idStore) {
            throw new EntityNotFoundException(sprintf('Could not find store by name "%s"', $storeName));
        }

        $this->idStoreCache[$storeName] = $idStore;
    }
}
