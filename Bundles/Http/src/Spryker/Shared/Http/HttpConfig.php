<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Http;

use Spryker\Shared\Kernel\AbstractSharedConfig;
use Symfony\Component\HttpFoundation\Request;

class HttpConfig extends AbstractSharedConfig
{
    /**
     * @var string
     */
    protected const HTTP_FRAGMENT_PATH = '/_fragment';

    protected const REQUEST_TRUSTED_HEADER_SET = Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO;

    /**
     * Specification:
     * - Provides secret key for Symfony URI Signer.
     * - `SPRYKER_ZED_REQUEST_TOKEN` used as a fallback to decrease migration effort and will be removed in next releases.
     *
     * @api
     *
     * @return string
     */
    public function getUriSignerSecret(): string
    {
        $uriSignerSecret = null;
        if (getenv('SPRYKER_ZED_REQUEST_TOKEN')) {
            $uriSignerSecret = getenv('SPRYKER_ZED_REQUEST_TOKEN');
        }

        $uriSignerSecret = $this->get(
            HttpConstants::URI_SIGNER_SECRET_KEY,
            $uriSignerSecret,
        );

        if (!$uriSignerSecret) {
            trigger_error(
                'Environment configuration `HttpConstants::URI_SIGNER_SECRET_KEY` must be set.'
                . ' Please, define `$config[HttpConstants::URI_SIGNER_SECRET_KEY] = getenv(\'SPRYKER_ZED_REQUEST_TOKEN\') ?: null;`'
                . ' in your `config_default.php` file.',
                E_USER_ERROR,
            );
        }

        return $uriSignerSecret;
    }

    /**
     * @api
     *
     * @return string
     */
    public function getHttpFragmentPath(): string
    {
        return static::HTTP_FRAGMENT_PATH;
    }

    /**
     * @api
     *
     * @return string|null
     */
    public function getHIncludeRendererGlobalTemplate(): ?string
    {
        return null;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getTrustedHeaderSet(): int
    {
        return static::REQUEST_TRUSTED_HEADER_SET;
    }
}
