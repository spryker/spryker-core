<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use SprykerEngine\Shared\Dto\LocaleDto;

interface CategoryNodeProcessorInterface
{
    /**
     * @param array $categoryNodes
     * @param LocaleDto $locale
     *
     * @return mixed
     */
    public function process(array $categoryNodes, LocaleDto $locale);
}