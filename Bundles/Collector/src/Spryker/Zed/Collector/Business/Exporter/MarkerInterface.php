<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;

interface MarkerInterface
{

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $locale);

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param string $timestamp
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $locale, $timestamp);

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTimestamps(array $keys);

}
