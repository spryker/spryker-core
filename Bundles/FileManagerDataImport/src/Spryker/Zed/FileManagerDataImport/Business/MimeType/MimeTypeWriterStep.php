<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FileManagerDataImport\Business\MimeType;

use Orm\Zed\FileManager\Persistence\SpyMimeTypeQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\FileManagerDataImport\Business\DataSet\MimeTypeDataSetInterface;

class MimeTypeWriterStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $mimeTypeEntity = SpyMimeTypeQuery::create()
            ->filterByName($dataSet[MimeTypeDataSetInterface::KEY_NAME])
            ->findOneOrCreate();

        $mimeTypeEntity->setIsAllowed($dataSet[MimeTypeDataSetInterface::KEY_IS_ALLOWED]);
        $mimeTypeEntity->save();
    }
}
