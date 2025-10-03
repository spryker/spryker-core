<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantRegistrationRequest\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface MerchantRegistrationRequestToZedRequestClientInterface
{
    /**
     * @param array<mixed>|null $requestOptions
     */
    public function call(string $url, TransferInterface $object, ?array $requestOptions = null): TransferInterface;
}
