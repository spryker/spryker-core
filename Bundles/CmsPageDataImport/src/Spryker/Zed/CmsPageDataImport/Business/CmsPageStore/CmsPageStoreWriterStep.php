<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CmsPageDataImport\Business\CmsPageStore;

use Orm\Zed\Cms\Persistence\SpyCmsPageStoreQuery;
use Spryker\Zed\CmsPageDataImport\Business\DataSet\CmsPageStoreDataSet;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsPageStoreWriterStep implements DataImportStepInterface
{
    public const BULK_SIZE = 100;

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        (new SpyCmsPageStoreQuery())
            ->filterByFkCmsPage($dataSet[CmsPageStoreDataSet::ID_CMS_PAGE])
            ->filterByFkStore($dataSet[CmsPageStoreDataSet::ID_STORE])
            ->findOneOrCreate()
            ->save();
    }
}
