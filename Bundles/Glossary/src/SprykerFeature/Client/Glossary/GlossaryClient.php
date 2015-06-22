<?php

namespace SprykerFeature\Client\Glossary;

use SprykerEngine\Client\Kernel\AbstractClient;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GlossaryClient extends AbstractClient
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
