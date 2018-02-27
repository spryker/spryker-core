<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Company\Dependency\Client;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface CompanyToZedRequestClientInterface
{
    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|null $requestOptions
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer|\Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null): CompanyResponseTransfer;
}
