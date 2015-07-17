<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use SprykerEngine\Client\Kernel\Service\AbstractClient;
use SprykerFeature\Client\Glossary\Service\Storage\GlossaryStorageInterface;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class GlossaryClient extends AbstractClient implements GlossaryClientInterface
{

    /**
     * @param string $keyName
     * @param array $parameters
     * @param string $localeName
     *
     * @return string
     */
    public function translate($keyName, array $parameters = [], $localeName)
    {
        return $this->createTranslator($localeName)->translate($keyName, $parameters);
    }

    /**
     * @param $localeName
     *
     * @return GlossaryStorageInterface
     */
    private function createTranslator($localeName)
    {
        return $this->getDependencyContainer()->createTranslator($localeName);
    }

}
