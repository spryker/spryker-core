<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Jwt;

use Generated\Shared\Transfer\JwtTokenTransfer;

class JwtTokenParser implements JwtTokenParserInterface
{
    /**
     * @var \Spryker\Service\Oauth\Jwt\JwtTokenDecoderInterface
     */
    protected $jwtTokenDecoder;

    /**
     * @param $jwtTokenDecoder
     */
    public function  __construct(JwtTokenDecoderInterface $jwtTokenDecoder)
    {
        $this->jwtTokenDecoder = $jwtTokenDecoder;
    }

    /**
     * @param string $jwtToken
     *
     * @return \Generated\Shared\Transfer\JwtTokenTransfer
     */
    public function parse(string $jwtToken): JwtTokenTransfer
    {
        $data = $this->splitJwt($jwtToken);
        if (!$data) {
            return null;
        }

        $header = $this->parseHeader($data[0]);
        $claims = $this->parseClaims($data[1]);
        $signature = $this->parseSignature($header, $data[2]);

        foreach ($claims as $name => $value) {
            if (isset($header[$name])) {
                $header[$name] = $value;
            }
        }

        if ($signature === null) {
            unset($data[2]);
        }

        return $this->mapJwtTokenTransfer($header, $claims, $signature, $data);
    }

    /**
     * Splits the JWT string into an array
     *
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
     * Parses the header from a string
     *
     * @param string $data
     *
     * @return array
     */
    protected function parseHeader($data): array
    {
        $header = $this->jwtTokenDecoder->jsonDecode($this->jwtTokenDecoder->base64UrlDecode($data));

        return $header;
    }

    /**
     * Parses the claim set from a string
     *
     * @param string $data
     *
     * @return array
     */
    protected function parseClaims($data): array
    {
        $claims = $this->jwtTokenDecoder->jsonDecode($this->jwtTokenDecoder->base64UrlDecode($data));

        return $claims;
    }

    /**
     * Returns the signature from given data
     *
     * @param array $header
     * @param string $data
     *
     * @return string
     */
    protected function parseSignature(array $header, $data): string
    {
        if ($data == '' || !isset($header['alg']) || $header['alg'] == 'none') {
            return null;
        }

        $hash = $this->jwtTokenDecoder->base64UrlDecode($data);

        return $hash;
    }

    /**
     * @param $headers
     * @param $claims
     * @param $signature
     * @param $payload
     *
     * @return \Generated\Shared\Transfer\JwtTokenTransfer
     */
    protected function mapJwtTokenTransfer($headers, $claims, $signature, $payload): JwtTokenTransfer
    {
        return (new JwtTokenTransfer())
            ->setHeaders($headers)
            ->setClaims($claims)
            ->setSignature($signature)
            ->setPayload($payload);
    }
}
