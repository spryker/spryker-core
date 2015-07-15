<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;

interface UrlBuilderInterface
{

    /**
     * @param array $urlResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildUrls(array $urlResultSet, LocaleTransfer $locale);

}
