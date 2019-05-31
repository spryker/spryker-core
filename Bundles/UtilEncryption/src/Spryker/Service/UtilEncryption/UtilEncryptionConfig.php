<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilEncryption;

use Spryker\Service\Kernel\AbstractBundleConfig;

class UtilEncryptionConfig extends AbstractBundleConfig
{
    protected const OPEN_SSL_ENCRYPTION_METHOD = 'AES256';

    /**
     * @return string
     */
    public function getDefaultOpenSslEncryptionMethod(): string
    {
        return static::OPEN_SSL_ENCRYPTION_METHOD;
    }
}
