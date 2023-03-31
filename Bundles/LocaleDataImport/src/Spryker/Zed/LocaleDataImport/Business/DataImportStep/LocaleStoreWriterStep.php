<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\LocaleDataImport\Business\DataImportStep;

use Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\LocaleDataImport\Business\DataSet\LocaleDataSetInterface;

class LocaleStoreWriterStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed>
     */
    protected $localeStoreQuery;

    /**
     * @param \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed> $localeStoreQuery
     */
    public function __construct(SpyLocaleStoreQuery $localeStoreQuery)
    {
        $this->localeStoreQuery = $localeStoreQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->localeStoreQuery
            ->clear()
            ->filterByFkLocale($dataSet[LocaleDataSetInterface::ID_LOCALE])
            ->filterByFkStore($dataSet[LocaleDataSetInterface::ID_STORE])
            ->findOneOrCreate()
            ->save();
    }
}
