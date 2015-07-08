<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Client\Glossary\Service;

use Generated\Client\Ide\FactoryAutoCompletion\Glossary;
use SprykerFeature\Client\Storage\Service\StorageClientInterface;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerEngine\Client\Kernel\Service\AbstractDependencyContainer;

/**
 * @method Glossary getFactory()
 */
class GlossaryDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return StorageClientInterface
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
