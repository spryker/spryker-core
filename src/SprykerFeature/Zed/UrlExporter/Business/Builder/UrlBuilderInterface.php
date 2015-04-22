<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

interface UrlBuilderInterface
{
    /**
     * @param array $urlResultSet
     * @param string $locale
     *
     * @return array
     */
    public function buildUrls(array $urlResultSet, $locale);
}
