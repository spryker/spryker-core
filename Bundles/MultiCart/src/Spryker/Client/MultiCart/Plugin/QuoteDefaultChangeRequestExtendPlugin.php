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
     * - Takes quote id form params and replace it in quote change request.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PersistentCartChangeTransfer $cartChangeTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\PersistentCartChangeTransfer
     */
    public function extend(PersistentCartChangeTransfer $cartChangeTransfer, array $params = []): PersistentCartChangeTransfer
    {
        if (!empty($params['quote-id'])) {
            $cartChangeTransfer->setIdQuote($params['quote-id']);
        }
        return $cartChangeTransfer;
    }
}
