<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport\Business\DataImportStep;

use Orm\Zed\Store\Persistence\Base\SpyStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\LocaleDataImport\Business\DataSet\LocaleDataSetInterface;

class DefaultLocaleStoreWriterStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Store\Persistence\Base\SpyStoreQuery<\Orm\Zed\Store\Persistence\SpyStore>
     */
    protected $storeQuery;

    /**
     * @param \Orm\Zed\Store\Persistence\Base\SpyStoreQuery<mixed> $storeQuery
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
        $this->storeQuery
            ->clear()
            ->filterByName($dataSet[LocaleDataSetInterface::COLUMN_STORE_NAME])
            ->findOneOrCreate()
            ->setFkLocale($dataSet[LocaleDataSetInterface::ID_LOCALE])
            ->save();
    }
}
