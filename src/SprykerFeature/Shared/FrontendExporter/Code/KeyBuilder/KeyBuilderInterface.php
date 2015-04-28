<?php

namespace SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder;

interface KeyBuilderInterface
{
    /**
     * @param mixed $data
     * @param string $localeName
     *
     * @return string
     */
    public function generateKey($data, $localeName);
}
