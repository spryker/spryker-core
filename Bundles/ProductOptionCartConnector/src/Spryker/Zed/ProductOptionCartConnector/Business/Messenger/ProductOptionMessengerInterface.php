<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOptionCartConnector\Business\Messenger;

interface ProductOptionMessengerInterface
{
    /**
     * @param string $sku
     * @param array<string, \Generated\Shared\Transfer\MessageTransfer> $messageTransfersIndexedBySku
     *
     * @return array<string, \Generated\Shared\Transfer\MessageTransfer>
     */
    public function addInfoMessageInactiveProductOptionItemRemoved(
        string $sku,
        array $messageTransfersIndexedBySku
    ): array;
}
