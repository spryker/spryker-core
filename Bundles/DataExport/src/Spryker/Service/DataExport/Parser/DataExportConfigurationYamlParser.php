<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport\Parser;

use Generated\Shared\Transfer\DataExportConfigurationsTransfer;
use Generated\Shared\Transfer\DataExportConfigurationTransfer;
use Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceInterface;
use Spryker\Service\DataExport\Mapper\DataExportConfigurationMapperInterface;

class DataExportConfigurationYamlParser implements DataExportConfigurationParserInterface
{
    protected const HOOK_KEY_TIMESTAMP = 'timestamp';
    protected const HOOK_KEY_APPLICATION_ROOT_DIR = 'application_root_dir';

    /**
     * @var \Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceInterface
     */
    protected $utilDataReaderService;

    /**
     * @var \Spryker\Service\DataExport\Mapper\DataExportConfigurationMapperInterface
     */
    protected $dataExportConfigurationMapper;

    /**
     * @param \Spryker\Service\DataExport\Dependency\Service\DataExportToUtilDataReaderServiceInterface $utilDataReaderService
     * @param \Spryker\Service\DataExport\Mapper\DataExportConfigurationMapperInterface $dataExportConfigurationMapper
     */
    public function __construct(
        DataExportToUtilDataReaderServiceInterface $utilDataReaderService,
        DataExportConfigurationMapperInterface $dataExportConfigurationMapper
    ) {
        $this->utilDataReaderService = $utilDataReaderService;
        $this->dataExportConfigurationMapper = $dataExportConfigurationMapper;
    }

    /**
     * @param string $fileName
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    public function parseConfigurationFile(string $fileName): DataExportConfigurationsTransfer
    {
        $yamlBatchIterator = $this->utilDataReaderService->getYamlBatchIterator($fileName);
        $configData = $yamlBatchIterator->current();

        $dataExportConfigurationsTransfer = $this->dataExportConfigurationMapper
            ->mapDataExportConfigurationDataToDataExportConfigurationsTransfer(
                $configData,
                new DataExportConfigurationsTransfer()
            );

        return $this->addDataExportConfigurationHooks($dataExportConfigurationsTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\DataExportConfigurationsTransfer $dataExportConfigurationsTransfer
     *
     * @return \Generated\Shared\Transfer\DataExportConfigurationsTransfer
     */
    protected function addDataExportConfigurationHooks(DataExportConfigurationsTransfer $dataExportConfigurationsTransfer): DataExportConfigurationsTransfer
    {
        $defaultDataExportConfigurationTransfer = $dataExportConfigurationsTransfer->getDefaults();
        if ($defaultDataExportConfigurationTransfer === null) {
            $defaultDataExportConfigurationTransfer = new DataExportConfigurationTransfer();
        }

        $defaultDataExportConfigurationTransfer->addHook(static::HOOK_KEY_TIMESTAMP, time());
        $defaultDataExportConfigurationTransfer->addHook(static::HOOK_KEY_APPLICATION_ROOT_DIR, APPLICATION_ROOT_DIR);

        return $dataExportConfigurationsTransfer->setDefaults($defaultDataExportConfigurationTransfer);
    }
}
