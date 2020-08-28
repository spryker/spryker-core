<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Dependency\Service;

class DataImportToUtilDataReaderServiceBridge implements DataImportToUtilDataReaderServiceInterface
{
    /**
     * @var \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface
     */
    protected $utilDataReaderService;

    /**
     * @param \Spryker\Service\UtilDataReader\UtilDataReaderServiceInterface $utilDataReaderService
     */
    public function __construct($utilDataReaderService)
    {
        $this->utilDataReaderService = $utilDataReaderService;
    }

    /**
     * @param string $fileName
     * @param int $chunkSize
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function getYamlBatchIterator($fileName, $chunkSize = -1)
    {
        return $this->utilDataReaderService->getYamlBatchIterator($fileName, $chunkSize);
    }
}
