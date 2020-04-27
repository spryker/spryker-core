<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\DataExport;

use Generated\Shared\Transfer\DataExportResultDocumentTransfer;
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
        // This method MAY need to be exploded to `readExportConfiguration` and `readExportConfigurations` to have a typed return
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
     * @param array $writeConfiguration
     * @param array $data
     *
     * @return array
     */
    public function writeBatch(array $exportConfiguration, array $writeConfiguration, array $data): array
    {
        // Note: here should be a 2 level plugin stack (Writer + Connection) with an in-built fallback to spryker/flysystem + spryker/csv
        if ($exportConfiguration['connection']['type'] === 'local') {
            if ($exportConfiguration['writer']['type'] === 'csv') {
                $destination = $exportConfiguration['connection']['params']['export_root_dir'] . '/' . $exportConfiguration['destination'];
                $destination = $this->resolveDestination($destination, $exportConfiguration);

                $this->make_dir($destination);
                $file = fopen($destination, $writeConfiguration['mode']); // We need to have a clever solution so each writer and connection can have a strict structure!
                if ($writeConfiguration['mode'] === 'w') {
                    fputcsv($file, $data['headers']);
                }
                foreach($data['rows'] as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);

                $result = (new DataExportResultDocumentTransfer())
                    ->setName($destination)
                    ->setObjectCount(count($data['rows']));

                return [$result];

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
        // dynamic resolution is necessary => use keys and values from the assoc array
        return str_replace(
            [
                '{data_entity}',
                '{timestamp}',
                '{extension}',
                '{application_root_dir}',
            ],
            [
                $exportConfiguration['data_entity'], // this should come from hooks as well
                $exportConfiguration['hooks']['timestamp'],
                $exportConfiguration['hooks']['extension'],
                $exportConfiguration['hooks']['application_root_dir'],
            ],
            $destination
        );
    }

    protected function make_dir( $fullyQualifiedFileName, $permissions = 0777 ): bool {
        // This will be resovled with spryker/flysystem
        $path = dirname($fullyQualifiedFileName);
        return is_dir( $path ) || mkdir( $path, $permissions, true );
    }
}
