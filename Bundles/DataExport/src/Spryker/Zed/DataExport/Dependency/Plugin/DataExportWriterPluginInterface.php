<?php


namespace Spryker\Zed\DataExport\Dependency\Plugin;


interface DataExportWriterPluginInterface
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
     * @return string
     */
    public function getExtension(array $exportConfiguration):string;
}
