<?php

namespace SprykerFeature\Zed\ProductOptionExporter\Dependency\Facade;

use Generated\Shared\Transfer\LocaleTransfer;

/**
 * (c) Spryker Systems GmbH copyright protected
 */
interface ProductOptionExporterToLocaleInterface
{

    /**
     * @param string $localeName
     *
     * @return LocaleTransfer
     */
    public function getLocale($localeName);
}
