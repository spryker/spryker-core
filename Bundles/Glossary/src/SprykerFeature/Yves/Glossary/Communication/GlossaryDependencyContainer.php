<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Communication;

use Generated\Yves\Ide\FactoryAutoCompletion\Glossary;
use SprykerEngine\Yves\Kernel\AbstractDependencyContainer;

/**
 * @method Glossary getFactory()
 */
class GlossaryDependencyContainer extends AbstractDependencyContainer
{

    /**
     * @return TranslationServiceProvider
     */
    public function createTranslationServiceProvider()
    {
        return $this->getFactory()->createTranslationServiceProvider($this->getFactory(), $this->getLocator());
    }

}
