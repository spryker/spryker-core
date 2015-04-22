<?php

namespace SprykerFeature\Sdk\Glossary;

use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GlossarySdk extends AbstractSdk
{
    /**
     * @param $locale
     *
     * @return Translator
     */
    public function createTranslator($locale)
    {
        return $this->getDependencyContainer()->createTranslator($locale);
    }
}
