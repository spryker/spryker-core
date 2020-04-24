<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport;

use Spryker\Service\Kernel\AbstractService;
use Spryker\Service\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Service\UtilDataReader\UtilDataReaderService;

/**
 * @method \Spryker\Service\DataExport\DataExportFactory getFactory()
 * @method \Spryker\Service\DataExport\DataExportConfig getConfig()
 */
class DataExportService extends AbstractService implements DataExportServiceInterface
{
    use BundleConfigResolverAwareTrait;

    public function readConfiguration(string $configurationPath): array
    {
        $configurationIterator = (new UtilDataReaderService())->getYamlBatchIterator($configurationPath)->current();

        $configuration = [];
        foreach($configurationIterator as $k => $v) {
            $configuration[$k] = $v;
        }

        return $configuration;
    }

    /**
     * @param array $exportConfiguration
     * @param $defaultExportConfigurations
     *
     * @return array
     */
    public function mergeExportConfigurationByDataEntity(array $exportConfiguration, $defaultExportConfigurations): array
    {
        $searchDataEntity = $exportConfiguration['data_entity'];
        foreach ($defaultExportConfigurations['actions'] as $defaultExportConfiguration) {
            if ($searchDataEntity === $defaultExportConfiguration['data_entity']) {
                return $exportConfiguration + $defaultExportConfiguration;
            }
        }

        return $exportConfiguration;
    }

    /**
     * @param array $exportConfiguration
     * @param array $data
     *
     * @return array
     */
    public function write(array $exportConfiguration, array $writeConfiguration, array $data): array
    {

        if ($exportConfiguration['connection']['type'] === 'local') { // FlySystem => spryker/flysystem
            if ($exportConfiguration['writer']['type'] === 'csv') { // Leage/Csv => spryker/csv
                $destination = $exportConfiguration['connection']['params']['export_root_dir'] . '/' . $exportConfiguration['destination'];
                $destination = $this->resolveDestination($destination, $exportConfiguration);

                $this->make_dir($destination); // flysystem
                $file = fopen($destination, $writeConfiguration['mode']);
                foreach($data['rows'] as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);

                return [$destination, count($data['rows'])];
            } else if ($exportConfiguration['writer'] === 'xml') {
                //  fwrite($file, $data['header']);
                foreach($data['rows'] as $objects) {
                //    fwrite($file, $row);
                }
            }
        }

        return [];
    }

    protected function resolveDestination(string $destination, array $exportConfiguration): string
    {
        return str_replace(
            [
                '{data_entity}',
                '{timestamp}',
                '{extension}',
                '{application_root_dir}',
            ],
            [
                $exportConfiguration['data_entity'],
                $exportConfiguration['hidden']['timestamp'],
                $exportConfiguration['hidden']['extension'],
                $exportConfiguration['hidden']['application_root_dir'],
            ],
            $destination
        );
    }

    protected function make_dir( $fullyQualifiedFileName, $permissions = 0777 ): bool {
        $path = dirname($fullyQualifiedFileName);
        return is_dir( $path ) || mkdir( $path, $permissions, true );
    }
}
