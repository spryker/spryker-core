<?php

namespace SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder;

interface KeyBuilderInterface
{
    /**
     * @param mixed     $data
     * @param string    $locale
     *
     * @return string
     */
    public function generateKey($data, $locale);
}
