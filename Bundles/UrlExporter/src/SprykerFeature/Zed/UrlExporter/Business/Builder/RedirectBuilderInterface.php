<?php

namespace SprykerFeature\Zed\UrlExporter\Business\Builder;

use SprykerEngine\Shared\Dto\LocaleDto;

interface RedirectBuilderInterface
{
    /**
     * @param array $redirectResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildRedirects(array $redirectResultSet, LocaleDto $locale);
}
