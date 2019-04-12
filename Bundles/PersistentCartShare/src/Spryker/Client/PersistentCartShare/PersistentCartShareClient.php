<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\QuotePreviewRequestTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PersistentCartShare\PersistentCartShareFactory getFactory()
 */
class PersistentCartShareClient extends AbstractClient implements PersistentCartShareClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteForPreview(string $resourceShareUuid): QuoteResponseTransfer
    {
        $quotePreviewRequestTransfer = new QuotePreviewRequestTransfer();
        $quotePreviewRequestTransfer->setResourceShareUuid($resourceShareUuid);

        $quoteResponseTransfer = $this->getFactory()->createZedPersistentCartShareStub()
            ->getQuoteForPreview($quotePreviewRequestTransfer);

        return $quoteResponseTransfer;
    }
}
