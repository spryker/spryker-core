<?php

namespace SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder;

use SprykerEngine\Shared\Dto\LocaleDto;

interface KeyBuilderInterface
{
    /**
     * @param mixed $data
     * @param LocaleDto $locale
     *
     * @return string
     */
    public function generateKey($data, LocaleDto $locale);
}
