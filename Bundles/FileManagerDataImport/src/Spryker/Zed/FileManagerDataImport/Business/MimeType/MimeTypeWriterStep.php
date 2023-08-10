<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\FileManagerDataImport\Business\MimeType;

use Orm\Zed\FileManager\Persistence\Base\SpyMimeType;
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

        if ($this->hasExtensionsField()) {
            $mimeTypeEntity->setExtensions($dataSet[MimeTypeDataSetInterface::KEY_EXTENSIONS]);
        }

        $mimeTypeEntity->save();
    }

    /**
     * @deprecated Will be removed in the next major without replacement.
     *
     * @return bool
     */
    protected function hasExtensionsField(): bool
    {
        return property_exists(SpyMimeType::class, 'extensions');
    }
}
