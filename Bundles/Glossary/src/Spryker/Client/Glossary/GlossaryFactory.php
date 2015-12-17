<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Client\Glossary;

use Spryker\Client\Glossary\KeyBuilder\GlossaryKeyBuilder;
use Spryker\Client\Glossary\Storage\GlossaryStorage;
use Spryker\Client\Glossary\Storage\GlossaryStorageInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\Storage\StorageClientInterface;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class GlossaryFactory extends AbstractFactory
{

    /**
     * @param string $locale
     *
     * @return GlossaryStorageInterface
     */
    public function createTranslator($locale)
    {
        return new GlossaryStorage(
            $this->getStorage(),
            $this->getKeyBuilder(),
            $locale
        );
    }

    /**
     * @return StorageClientInterface
     */
    private function getStorage()
    {
        return $this->getProvidedDependency(GlossaryDependencyProvider::KV_STORAGE);
    }

    /**
     * @return KeyBuilderInterface
     */
    private function getKeyBuilder()
    {
        return new GlossaryKeyBuilder();
    }

}
