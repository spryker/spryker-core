<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use DateTime;
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
     * @param \DateTime $timestamp
     *
     * @return void
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $locale, DateTime $timestamp);

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTimestamps(array $keys);
}
