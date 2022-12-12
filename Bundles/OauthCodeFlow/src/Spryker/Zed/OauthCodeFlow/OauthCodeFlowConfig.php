<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCodeFlow;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthCodeFlowConfig extends AbstractBundleConfig
{
    /**
     * @var string
     */
    public const GRANT_TYPE_AUTHORIZATION_CODE = 'authorization_code';

    /**
     * @uses \Spryker\Shared\Oauth\OauthConstants::ENCRYPTION_KEY
     *
     * @var string
     */
    protected const ENCRYPTION_KEY = 'ENCRYPTION_KEY';

    /**
     * Specification:
     * - Sets the interval for how long is the auth code is valid, this will be feed to \DateTime object.
     *
     * @api
     *
     * @return string
     */
    public function getAuthCodeTTL(): string
    {
        return 'PT1M';
    }

    /**
     * Specification:
     * - Encryption key used to encrypt data when generates authorization code.
     *
     * @api
     *
     * @return string
     */
    public function getEncryptionKey(): string
    {
        return $this->get(static::ENCRYPTION_KEY);
    }
}
