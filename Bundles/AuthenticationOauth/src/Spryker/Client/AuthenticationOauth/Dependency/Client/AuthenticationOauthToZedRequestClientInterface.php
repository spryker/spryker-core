<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthenticationOauth\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface AuthenticationOauthToZedRequestClientInterface
{
    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array<mixed>|int|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call(string $url, TransferInterface $object, $requestOptions = null): TransferInterface;
}
