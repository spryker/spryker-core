<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\UserLocale;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class UserLocaleConfig extends AbstractBundleConfig
{
    protected const DEFAULT_LOCALE = 'en_US';

    /**
     * @return string
     */
    public function getDefaultLocale(): string
    {
        return static::DEFAULT_LOCALE;
    }

    /**
     * @return array
     */
    public function getInstallerUsers(): array
    {
        return [
            [
                'firstName' => 'Admin',
                'lastName' => 'German',
                'password' => 'change123',
                'userName' => 'admin_de@spryker.com',
                'locale' => 'de_DE',
            ],
        ];
    }
}
