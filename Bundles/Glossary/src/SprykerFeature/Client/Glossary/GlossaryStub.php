<?php

namespace SprykerFeature\Client\Glossary;

use SprykerEngine\Client\Kernel\AbstractStub;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GlossaryStub extends AbstractStub
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
