<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\FileManagerDataImport\Business\MimeType;

use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface;
use Spryker\Zed\FileManagerDataImport\Business\DataSet\MimeTypeDataSetInterface;

class MimeTypeExtensionsEncodeStep implements DataImportStepInterface
{
    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface
     */
    protected DataImportToUtilEncodingServiceInterface $utilEncodingService;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(DataImportToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $extensions = $dataSet[MimeTypeDataSetInterface::KEY_EXTENSIONS] ?? null;

        if (!$extensions) {
            $dataSet[MimeTypeDataSetInterface::KEY_EXTENSIONS] = $this->utilEncodingService->encodeJson([]);

            return;
        }

        $extensions = explode(',', $extensions);
        $dataSet[MimeTypeDataSetInterface::KEY_EXTENSIONS] = $this->utilEncodingService->encodeJson($extensions);
    }
}
