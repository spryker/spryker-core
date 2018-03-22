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
     * @param \Spryker\Zed\Messenger\Dependency\Plugin\TranslationPluginInterface $translationPlugin
     */
    public function __construct(TranslationPluginInterface $translationPlugin)
    {
        $this->translationPlugin = $translationPlugin;
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
            $translation = $this->translationPlugin->translate($keyName, $data);
        }

        return $translation;
    }
}
