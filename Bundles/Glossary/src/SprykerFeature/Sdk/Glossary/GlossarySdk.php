<?php

namespace SprykerFeature\Sdk\Glossary;

use SprykerEngine\Sdk\Kernel\AbstractSdk;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GlossarySdk extends AbstractSdk
{
    /**
     * @param string $localeName
     *
     * @return Translator
     */
    public function createTranslator($localeName)
    {
        return $this->getDependencyContainer()->createTranslator($localeName);
    }
}
