<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MultiCart\Plugin;

use Generated\Shared\Transfer\PersistentCartChangeTransfer;
use Spryker\Client\PersistentCart\Dependency\Plugin\ChangeRequestExtendPluginInterface;

class QuoteDefaultChangeRequestExtendPlugin implements ChangeRequestExtendPluginInterface
{
    /**
     * Specification:
     * - Takes quote id form item transfer options and replace it in quote change request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $cartChangeTransfer): PersistentCartChangeTransfer
    {
        return $cartChangeTransfer;
    }
}
