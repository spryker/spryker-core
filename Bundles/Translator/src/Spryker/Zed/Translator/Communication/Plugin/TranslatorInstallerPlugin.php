<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin;

use Spryker\Zed\Installer\Dependency\Plugin\InstallerPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class TranslatorInstallerPlugin extends AbstractPlugin implements InstallerPluginInterface
{
    /**
     * @api
     *
     * @return void
     */
    public function install(): void
    {
        $this->getFacade()->cleanTranslationCache();
        $this->getFacade()->generateTranslationCache();
    }
}
