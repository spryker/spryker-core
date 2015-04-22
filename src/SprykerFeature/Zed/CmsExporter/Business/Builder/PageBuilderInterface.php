<?php

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

interface PageBuilderInterface
{
    /**
     * @param array $pageResultSet
     * @param string $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, $locale);
}
