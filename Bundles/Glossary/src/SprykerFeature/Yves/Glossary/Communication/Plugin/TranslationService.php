<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Communication\Plugin;

use SprykerEngine\Yves\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Yves\Glossary\Communication\GlossaryDependencyContainer;
use SprykerFeature\Yves\Glossary\Communication\TranslationServiceProvider;

/**
 * @method GlossaryDependencyContainer getDependencyContainer()
 */
class TranslationService extends AbstractPlugin
{
    /**
     * @return TranslationServiceProvider
     */
    public function createTranslationServiceProvider()
    {
        return $this->getDependencyContainer()->createTranslationServiceProvider();
    }
}
