<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthDummy;

use Spryker\Zed\Kernel\AbstractBundleConfig;

class OauthDummyConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @var string
     */
    public const PROVIDER_NAME = 'dummy';

    /**
     * @var string
     */
    protected const EXPIRES_IN = '86400';

    /**
     * @var string
     */
    protected const SUBJECT = 'subject';

    /**
     * @var string
     */
    protected const STORE_REFERENCE_KEY = 'store_reference';

    /**
     * @api
     *
     * @return string
     */
    public function getPathToPublicKey(): string
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/dev_only_public.key';
    }

    /**
     * @api
     *
     * @return string
     */
    public function getPathToPrivateKey(): string
    {
        return APPLICATION_ROOT_DIR . '/config/Zed/dev_only_private.key';
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getAccessTokenCustomClaims(): array
    {
        return [
            'azp' => 'dev_tenant_oauth_client_id_1',
        ];
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExpiresIn(): string
    {
        return static::EXPIRES_IN;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSubject(): string
    {
        return static::SUBJECT;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getStoreReferenceKey(): string
    {
        return static::STORE_REFERENCE_KEY;
    }
}
