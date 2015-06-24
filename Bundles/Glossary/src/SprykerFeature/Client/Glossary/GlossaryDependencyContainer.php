<?php

namespace SprykerFeature\Client\Glossary;

use Generated\Client\Ide\FactoryAutoCompletion\Glossary;
use SprykerFeature\Client\KvStorage\Service\KvStorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerEngine\Client\Kernel\AbstractDependencyContainer;

/**
 * @method Glossary getFactory()
 */
class GlossaryDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return KvStorageClientInterface
     */
    protected function getKvStorage()
    {
        return $this->getLocator()->kvStorage()->client();
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
            $this->getKvStorage(),
            $this->getKeyBuilder(),
            $locale
        );
    }
}
