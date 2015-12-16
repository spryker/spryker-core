<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary;

use Spryker\Client\Kernel\AbstractClient;
use Spryker\Client\Glossary\Storage\GlossaryStorageInterface;

/**
 * @method GlossaryFactory getFactory()
 */
class GlossaryClient extends AbstractClient implements GlossaryClientInterface
{

    /**
     * @param string $id
     * @param string $localeName
     * @param array $parameters
     *
     * @return string
     */
    public function translate($id, $localeName, array $parameters = [])
    {
        return $this->createTranslator($localeName)->translate($id, $parameters);
    }

    /**
     * @param $localeName
     *
     * @return GlossaryStorageInterface
     */
    private function createTranslator($localeName)
    {
        return $this->getFactory()->createTranslator($localeName);
    }

}
