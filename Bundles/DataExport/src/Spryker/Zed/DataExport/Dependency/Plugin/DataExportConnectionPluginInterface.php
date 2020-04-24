<?php


namespace Spryker\Zed\DataExport\Dependency\Plugin;


interface DataExportConnectionPluginInterface
{
    /**
     * @param array $exportConfiguration
     *
     * @return bool
     */
    public function isApplicable(array $exportConfiguration): bool;

    /**
     * @param array $exportConfiguration
     *
     * @return DataExportConfigurationCheckResultTransfer
     */
    public function checkDataExportConfiguration(array $exportConfiguration);
}
