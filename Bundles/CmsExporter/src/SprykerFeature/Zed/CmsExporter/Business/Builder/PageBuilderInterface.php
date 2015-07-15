<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use Generated\Shared\Transfer\LocaleTransfer;

interface PageBuilderInterface
{

    /**
     * @param array $pageResultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, LocaleTransfer $locale);

}
