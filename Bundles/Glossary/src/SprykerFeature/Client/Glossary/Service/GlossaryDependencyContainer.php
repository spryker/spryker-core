<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use Generated\Client\Ide\FactoryAutoCompletion\GlossaryService;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerEngine\Client\Kernel\Service\AbstractServiceDependencyContainer;

/**
 * @method GlossaryService getFactory()
 */
class GlossaryDependencyContainer extends AbstractServiceDependencyContainer
{

    /**
     * @return StorageClientInterface
     */
    protected function getStorage()
    {
        return $this->getLocator()->storage()->client();
    }

    /**
     * @return KeyBuilderInterface
     */
    protected function getKeyBuilder()
    {
        return $this->getFactory()->createKeyBuilderGlossaryKeyBuilder();
    }

    /**
     * @param string $locale
     *
     * @return Translator
     */
    public function createTranslator($locale)
    {
        return $this->getFactory()->createTranslator(
            $this->getStorage(),
            $this->getKeyBuilder(),
            $locale
        );
    }

}
