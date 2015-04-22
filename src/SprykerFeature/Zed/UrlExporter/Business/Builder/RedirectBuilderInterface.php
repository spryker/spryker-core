<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

interface RedirectBuilderInterface
{
    /**
     * @param array $redirectResultSet
     * @param string $localeName
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, $localeName);
}
