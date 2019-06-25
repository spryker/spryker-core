<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Jwt;

use Generated\Shared\Transfer\JwtTokenTransfer;
use Spryker\Service\Oauth\Dependency\Service\OauthToUtilEncodingServiceInterface;

class JwtTokenParser implements JwtTokenParserInterface
{
    protected const KEY_ALG = 'alg';

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
     * @param string $jwtToken
     *
     * @return \Generated\Shared\Transfer\JwtTokenTransfer|null
     */
    public function parse(string $jwtToken): ?JwtTokenTransfer
    {
        $data = $this->splitJwt($jwtToken);
        if (!$data) {
            return null;
        }

        $header = $this->parseTokenData($data[0]);
        $claims = $this->parseTokenData($data[1]);
        $signature = $this->parseSignature($header, $data[2]);

        foreach ($claims as $name => $value) {
            if (isset($header[$name])) {
                $header[$name] = $value;
            }
        }

        if (!$signature) {
            unset($data[2]);
        }

        return $this->createJwtTokenTransfer($header, $claims, $signature, $data);
    }

    /**
     * @param string $jwt
     *
     * @return array|null
     */
    protected function splitJwt($jwt): ?array
    {
        if (!is_string($jwt)) {
            return null;
        }

        $data = explode('.', $jwt);

        if (count($data) != 3) {
            return null;
        }

        return $data;
    }

    /**
     * @param string $data
     *
     * @return array
     */
    protected function parseTokenData(string $data): array
    {
        return $this->utilEncodingService->decodeJson($this->decodeBase64Url($data), true);
    }

    /**
     * @param array $header
     * @param string $data
     *
     * @return string|null
     */
    protected function parseSignature(array $header, $data): ?string
    {
        if ($data == '' || !isset($header[static::KEY_ALG]) || $header[static::KEY_ALG] == 'none') {
            return null;
        }

        $hash = $this->decodeBase64Url($data);

        return $hash;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    protected function decodeBase64Url(string $data): string
    {
        if ($remainder = strlen($data) % 4) {
            $data .= str_repeat('=', 4 - $remainder);
        }

        return base64_decode(strtr($data, '-_', '+/'));
    }

    /**
     * @param array $headers
     * @param array $claims
     * @param string|null $signature
     * @param array $payload
     *
     * @return \Generated\Shared\Transfer\JwtTokenTransfer
     */
    protected function createJwtTokenTransfer(
        array $headers,
        array $claims,
        ?string $signature,
        array $payload
    ): JwtTokenTransfer {
        return (new JwtTokenTransfer())
            ->setHeaders($headers)
            ->setClaims($claims)
            ->setSignature($signature)
            ->setPayload($payload);
    }
}
