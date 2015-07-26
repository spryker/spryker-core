<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Dependency\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;

interface DataProcessorPluginInterface
{

    /**
     * @return string
     */
    public function getProcessableType();

    /**
     * @param array $resultSet
     * @param array $processedResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function processData(array &$resultSet, array $processedResultSet, LocaleTransfer $locale);

}
