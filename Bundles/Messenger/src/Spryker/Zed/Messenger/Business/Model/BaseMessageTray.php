<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Spryker\Zed\Translator\Communication\Plugin\Messenger\TranslationPlugin;

class BaseMessageTray
{
    /**
     * @var \Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface[]
     */
    protected $translationPlugins;

    /**
     * @var \Spryker\Zed\Translator\Communication\Plugin\Messenger\TranslationPlugin
     */
    protected $baseTranslationPlugin;

    /**
     * @param \Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface[] $translationPlugins
     */
    public function __construct(array $translationPlugins)
    {
        $this->translationPlugins = $translationPlugins;
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    protected function translate($keyName, array $data = []): string
    {
        foreach ($this->translationPlugins as $translationPlugin) {
            if ($translationPlugin->hasKey($keyName)) {
                return $translationPlugin->translate($keyName, $data);
            }

            if ($translationPlugin instanceof TranslationPlugin) {
                $this->baseTranslationPlugin = $translationPlugin;
            }
        }

        if ($this->baseTranslationPlugin !== null) {
            return $this->baseTranslationPlugin->translate($keyName, $data);
        }

        return $keyName;
    }
}
