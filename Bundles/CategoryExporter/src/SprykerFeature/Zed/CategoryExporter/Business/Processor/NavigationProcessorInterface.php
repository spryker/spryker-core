<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;

interface NavigationProcessorInterface
{

    /**
     * @param array $categoryNodes
     * @param LocaleDto $locale
     *
     * @return array
     */
    public function process(array $categoryNodes, LocaleDto $locale);
}
