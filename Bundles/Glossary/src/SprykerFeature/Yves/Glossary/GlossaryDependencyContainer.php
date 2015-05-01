<?php

namespace SprykerFeature\Yves\Glossary;

use Generated\Yves\Ide\FactoryAutoCompletion\Glossary;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;

class GlossaryDependencyContainer extends AbstractDependencyContainer
{
    /**
     * @var Glossary
     */
    protected $factory;

    /**
     * @return TranslationServiceProvider
     */
    public function createTranslationServiceProvider()
    {
        return $this->getFactory()->createTranslationServiceProvider($this->getFactory(), $this->getLocator());
    }
}
