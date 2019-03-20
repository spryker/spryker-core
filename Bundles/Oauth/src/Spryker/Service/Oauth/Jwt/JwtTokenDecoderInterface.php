<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Jwt;

interface JwtTokenDecoderInterface
{
    /**
     * @param string $json
     *
     * @return array|null
     */
    public function jsonDecode(string $json): ?array;

    /**
     * Decodes from base64url
     *
     * @param string $data
     * @return string
     */
    public function base64UrlDecode(string $data): string;
}
