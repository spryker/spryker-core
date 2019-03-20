<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Jwt;

use Spryker\Service\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;

class JwtTokenDecoder implements JwtTokenDecoderInterface
{
    protected const FORMAT_JSON = 'json';
    protected const FORMAT_BASE_64_URL = 'base64url';

    /**
     * @var \Spryker\Service\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \Spryker\Service\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(OauthToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param string $json
     *
     * @return array|null
     */
    public function jsonDecode(string $json): ?array
    {
        return $this->utilEncodingService->decodeFromFormat($json, static::FORMAT_JSON);
    }

    /**
     * Decodes from base64url
     *
     * @param string $data
     * @return string
     */
    public function base64UrlDecode(string $data): string
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }
}
