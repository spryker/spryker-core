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
     * @param string $locale
     *
     * @return \Spryker\Client\Glossary\Storage\GlossaryStorageInterface
     */
    public function createTranslator($locale)
    {
        return new GlossaryStorage(
            $this->getStorage(),
            $this->createKeyBuilder(),
            $locale
        );
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

    /**
     * @deprecated Use createKeyBuilder() instead.
     *
     * @return \Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface
     */
    protected function getKeyBuilder()
    {
        trigger_error('Deprecated, use createKeyBuilder() instead.', E_USER_DEPRECATED);

        return $this->createKeyBuilder();
    }

}
