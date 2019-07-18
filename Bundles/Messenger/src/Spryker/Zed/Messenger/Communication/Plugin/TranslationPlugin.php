<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger\Communication\Plugin;

use Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface;

class TranslationPlugin implements TranslationPluginInterface
{
    /**
     * @api
     *
     * @param string $keyName
     *
     * @return bool
     */
    public function hasKey($keyName)
    {
        return false;
    }

    /**
     * @api
     *
     * @param string $keyName
     * @param array $data
     *
     * @return string
     */
    public function translate($keyName, array $data = [])
    {
        return $keyName;
    }
}
