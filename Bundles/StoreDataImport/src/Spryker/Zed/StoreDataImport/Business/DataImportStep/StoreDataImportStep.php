<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StoreDataImport\Business\DataImportStep;

use Orm\Zed\Store\Persistence\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StoreDataImport\Business\DataSet\StoreDataSetInterface;

class StoreDataImportStep extends PublishAwareStep implements DataImportStepInterface
{
    /**
     * @uses \Spryker\Shared\StoreStorage\StoreStorageConfig::STORE_PUBLISH_WRITE
     *
     * @var string
     */
    protected const STORE_PUBLISH_WRITE_EVENT = 'Store.store.publish';

    /**
     * @var \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed>
     */
    protected SpyStoreQuery $storeQuery;

    /**
     * @param \Orm\Zed\Store\Persistence\SpyStoreQuery<mixed> $storeQuery
     */
    public function __construct(SpyStoreQuery $storeQuery)
    {
        $this->storeQuery = $storeQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $storeEntity = $this->storeQuery
            ->clear()
            ->filterByName($dataSet[StoreDataSetInterface::COLUMN_STORE_NAME])
            ->findOneOrCreate();

        $storeEntity->save();

        $this->addPublishEvents(
            static::STORE_PUBLISH_WRITE_EVENT,
            $storeEntity->getIdStore(),
        );
    }
}
