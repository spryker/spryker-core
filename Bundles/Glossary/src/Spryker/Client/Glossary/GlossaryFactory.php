<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary;

use Spryker\Client\Glossary\KeyBuilder\GlossaryKeyBuilder;
use Spryker\Client\Glossary\Storage\GlossaryStorage;
use Spryker\Client\Kernel\AbstractFactory;

class GlossaryFactory extends AbstractFactory
{

    /**
     * @var \Spryker\Client\Glossary\Storage\GlossaryStorageInterface[]
     */
    protected $translator = [];

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    public function createTranslator($localeName)
    {
        return new GlossaryStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $localeName
        );
    }

    /**
     * @param string $localeName
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    public function createCachedTranslator($localeName)
    {
        if (array_key_exists($localeName, $this->translator) === false) {
            $this->translator[$localeName] = $this->createTranslator($localeName);
        }

        return $this->translator[$localeName];
    }

    /**
     * @return \Spryker\Client\Storage\StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::KV_STORAGE);
    }

    /**
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function createKeyBuilder()
    {
        return new GlossaryKeyBuilder();
    }

}
