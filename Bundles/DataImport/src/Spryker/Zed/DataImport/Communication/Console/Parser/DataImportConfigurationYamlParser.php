<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Communication\Console\Parser;

use Generated\Shared\Transfer\DataImportConfigurationTransfer;
use Spryker\Zed\DataImport\Communication\Console\Mapper\DataImportConfigurationMapperInterface;
use Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilDataReaderServiceInterface;

class DataImportConfigurationYamlParser implements DataImportConfigurationParserInterface
{
    protected const CONFIGURATION_KEY_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilDataReaderServiceInterface
     */
    protected $utilDataReader;

    /**
     * @var \Spryker\Zed\DataImport\Communication\Console\Mapper\DataImportConfigurationMapperInterface
     */
    protected $dataImportConfigurationMapper;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Service\DataImportToUtilDataReaderServiceInterface $utilDataReader
     * @param \Spryker\Zed\DataImport\Communication\Console\Mapper\DataImportConfigurationMapperInterface $dataImportConfigurationMapper
     */
    public function __construct(
        DataImportToUtilDataReaderServiceInterface $utilDataReader,
        DataImportConfigurationMapperInterface $dataImportConfigurationMapper
    ) {
        $this->utilDataReader = $utilDataReader;
        $this->dataImportConfigurationMapper = $dataImportConfigurationMapper;
    }

    /**
     * @param string $filename
     *
     * @return \Generated\Shared\Transfer\DataImportConfigurationTransfer
     */
    public function parseConfigurationFile(string $filename): DataImportConfigurationTransfer
    {
        $yamlBatchIterator = $this->utilDataReader->getYamlBatchIterator($filename);
        $configData = $yamlBatchIterator->current();

        return $this->dataImportConfigurationMapper->mapDataImportConfigurationDataToDataImportConfigurationTransfer(
            $configData,
            new DataImportConfigurationTransfer()
        );
    }
}
