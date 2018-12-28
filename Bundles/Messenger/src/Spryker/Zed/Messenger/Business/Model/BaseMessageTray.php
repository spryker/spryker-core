<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Business\Model;

use Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface;

class BaseMessageTray
{
    /**
     * @var \Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface
     */
    protected $translationPlugin;

    /**
     * @var \Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface
     */
    protected $fallbackTranslationPlugin;

    /**
     * @param \Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface $translationPlugin
     * @param \Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface $fallbackTranslationPlugin
     */
    public function __construct(TranslationPluginInterface $translationPlugin, TranslationPluginInterface $fallbackTranslationPlugin)
    {
        $this->translationPlugin = $translationPlugin;
        $this->fallbackTranslationPlugin = $fallbackTranslationPlugin;
    }

    /**
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    protected function translate($keyName, array $data = [])
    {
        $translation = $keyName;
        if ($this->translationPlugin->hasKey($keyName)) {
            return $this->translationPlugin->translate($keyName, $data);
        }

        if ($this->fallbackTranslationPlugin->hasKey($keyName)) {
            return $this->fallbackTranslationPlugin->translate($keyName, $data);
        }

        return $translation;
    }
}
