<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Communication\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Yves\Glossary\Communication\TranslationServiceProvider;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class TranslationServicePlugin extends AbstractPlugin
{
    /**
     * @return TranslationServiceProvider
     */
    public function createTranslationServiceProvider()
    {
        return $this->getDependencyContainer()->createTranslationServiceProvider();
    }
}
