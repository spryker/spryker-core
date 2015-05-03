<?php

namespace SprykerFeature\Zed\FrontendExporter\Dependency\Plugin;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface DataProcessorPluginInterface
{
    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleDto $locale);
}
