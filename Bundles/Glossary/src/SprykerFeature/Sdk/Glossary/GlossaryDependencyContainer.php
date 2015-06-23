<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Sdk\Glossary;

use Generated\Sdk\Ide\FactoryAutoCompletion\Glossary;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Shared\Library\Storage\Adapter\KeyValue\ReadInterface;
use SprykerEngine\Sdk\Kernel\AbstractDependencyContainer;

class GlossaryDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @var Glossary
     */
    protected $factory;

    /**
     * @return ReadInterface
     */
    protected function getKvStorage()
    {
        return $this->getLocator()->kvStorage()->readClient()->getInstance();
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
