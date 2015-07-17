<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use Generated\Client\Ide\FactoryAutoCompletion\GlossaryService;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;
use SprykerFeature\Client\Glossary\GlossaryDependencyProvider;
use SprykerFeature\Client\Glossary\Service\Storage\GlossaryStorageInterface;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;

/**
 * @method GlossaryService getFactory()
 */
class GlossaryDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @param $locale
     *
     * @return GlossaryStorageInterface
     */
    public function createTranslator($locale)
    {
        return $this->getFactory()->createStorageGlossaryStorage(
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
        return $this->getProvidedDependency(GlossaryDependencyProvider::STORAGE);
    }

    /**
     * @return KeyBuilderInterface
     */
    private function getKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderGlossaryKeyBuilder();
    }

}
