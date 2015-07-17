<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;

interface RedirectBuilderInterface
{

    /**
     * @param array $redirectResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, LocaleTransfer $locale);

}
