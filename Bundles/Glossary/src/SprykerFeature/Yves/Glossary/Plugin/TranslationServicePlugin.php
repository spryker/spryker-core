<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Yves\Glossary\Plugin;

use SprykerEngine\Yves\Kernel\AbstractPlugin;
use SprykerFeature\Yves\Glossary\GlossaryDependencyContainer;
use SprykerFeature\Yves\Glossary\TranslationServiceProvider;

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
