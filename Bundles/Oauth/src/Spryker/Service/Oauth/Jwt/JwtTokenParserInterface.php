<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Oauth\Jwt;

use Generated\Shared\Transfer\JwtTokenTransfer;

interface JwtTokenParserInterface
{
    /**
     * @param string $jwtToken
     *
     * @return \Generated\Shared\Transfer\JwtTokenTransfer
     */
    public function parse(string $jwtToken): JwtTokenTransfer;
}
