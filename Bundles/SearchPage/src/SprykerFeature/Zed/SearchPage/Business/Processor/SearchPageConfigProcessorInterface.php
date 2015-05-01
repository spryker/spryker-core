<?php

namespace SprykerFeature\Zed\SearchPage\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;

interface SearchPageConfigProcessorInterface
{
    /**
     * @param array $configRaw
     * @param LocaleDto $localeDto
     *
     * @return array
     */
    public function processSearchPageConfig(array $configRaw, LocaleDto $localeDto);
}