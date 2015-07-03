<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;

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
