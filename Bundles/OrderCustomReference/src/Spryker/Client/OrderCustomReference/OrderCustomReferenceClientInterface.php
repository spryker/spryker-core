<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\OrderCustomReference;

use Generated\Shared\Transfer\QuoteResponseTransfer;

interface OrderCustomReferenceClientInterface
{
    /**
     * Specification:
     * - Sets Quote with QuoteTransfer::orderCustomReference.
     *
     * @api
     *
     * @param string|null $orderCustomReference
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function setOrderCustomReference(?string $orderCustomReference): QuoteResponseTransfer;
}
