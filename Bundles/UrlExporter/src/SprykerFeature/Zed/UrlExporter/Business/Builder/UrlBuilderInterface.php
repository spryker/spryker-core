<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use SprykerEngine\Shared\Locale\Dto\LocaleDto;

interface UrlBuilderInterface
{
    /**
     * @param array $urlResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildUrls(array $urlResultSet, LocaleDto $locale);
}
