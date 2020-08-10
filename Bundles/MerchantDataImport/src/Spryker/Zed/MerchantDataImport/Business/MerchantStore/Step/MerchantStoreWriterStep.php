<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantDataImport\Business\MerchantStore\Step;

use Orm\Zed\Merchant\Persistence\SpyMerchantStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\Merchant\Dependency\MerchantEvents;
use Spryker\Zed\MerchantDataImport\Business\MerchantStore\DataSet\MerchantStoreDataSetInterface;

class MerchantStoreWriterStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantStoreEntity = SpyMerchantStoreQuery::create()
            ->filterByFkMerchant($dataSet[MerchantStoreDataSetInterface::ID_MERCHANT])
            ->filterByFkStore($dataSet[MerchantStoreDataSetInterface::ID_STORE])
            ->findOneOrCreate();

        $merchantStoreEntity->save();

        $this->addPublishEvents(MerchantEvents::MERCHANT_PUBLISH, $merchantStoreEntity->getFkMerchant());
    }
}
