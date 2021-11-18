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
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $localeTransfer);

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \DateTime $timestamp
     *
     * @return void
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $localeTransfer, DateTime $timestamp);

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTimestamps(array $keys);
}
