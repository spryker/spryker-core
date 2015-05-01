<?php

namespace SprykerFeature\Zed\CmsExporter\Business\Builder;

use SprykerEngine\Shared\Dto\LocaleDto;

interface PageBuilderInterface
{
    /**
     * @param array $pageResultSet
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function buildPages(array $pageResultSet, LocaleDto $locale);
}
