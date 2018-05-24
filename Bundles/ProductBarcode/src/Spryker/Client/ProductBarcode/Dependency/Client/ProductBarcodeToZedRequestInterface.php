<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductBarcode\Dependency\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

interface ProductBarcodeToZedRequestInterface
{
    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param int|null $timeoutInSeconds
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|\Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function call($url, TransferInterface $object, $timeoutInSeconds = null);
}
