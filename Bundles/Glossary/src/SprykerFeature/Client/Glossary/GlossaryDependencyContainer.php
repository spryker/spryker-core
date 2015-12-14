<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary;

use SprykerFeature\Client\Glossary\KeyBuilder\GlossaryKeyBuilder;
use SprykerFeature\Client\Glossary\Storage\GlossaryStorage;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;
use SprykerFeature\Client\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Client\Storage\StorageClientInterface;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

class GlossaryDependencyContainer extends AbstractDependencyContainer
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
