<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use SprykerFeature\Client\Glossary\Service\KeyBuilder\GlossaryKeyBuilder;
use SprykerFeature\Client\Glossary\Service\Storage\GlossaryStorage;
use Generated\Client\Ide\FactoryAutoCompletion\GlossaryService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;

/**
 * @method GlossaryService getFactory()
 */
class GlossaryDependencyContainer extends AbstractServiceDependencyContainer
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
