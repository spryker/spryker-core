<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator\Communication\Plugin\Messenger;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface;
use Spryker\Zed\Translator\Business\Translator\Translator;

/**
 * @method \Spryker\Zed\Translator\Communication\TranslatorCommunicationFactory getFactory()
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 * @method \Spryker\Zed\Translator\Business\TranslatorFacadeInterface getFacade()
 */
class TranslationPlugin extends AbstractPlugin implements TranslationPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName): bool
    {
        $translator = $this->getTranslator();

        if ($translator instanceof Translator) {
            return $translator->getCatalogue($this->getLocaleName())->has($keyName);
        }

        return $translator->has($keyName, $this->getLocaleName());
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    public function translate($keyName, array $data = []): string
    {
        return $this->getTranslator()->trans($keyName, $data, null, $this->getLocaleName());
    }

    /**
     * @return string
     */
    protected function getLocaleName(): string
    {
        return $this->getFactory()->getLocaleFacade()->getCurrentLocaleName();
    }

    /**
     * deprecated `\Spryker\Zed\Translator\Business\Translator\Translator` should not be used anymore.
     *
     * @return \Spryker\Shared\TranslatorExtension\Dependency\Plugin\TranslatorPluginInterface|\Spryker\Zed\Translator\Business\Translator\Translator
     */
    protected function getTranslator()
    {
        return $this->getFactory()->getApplication()['translator'];
    }
}
