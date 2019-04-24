<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Generated\Shared\Transfer\ResourceShareTransfer;
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
        $resourceShareTransfer = (new ResourceShareTransfer())
            ->setUuid($resourceShareUuid);
        $resourceShareRequestTransfer = (new ResourceShareRequestTransfer())
            ->setResourceShare($resourceShareTransfer);

        $quoteResponseTransfer = $this->getFactory()
            ->createZedPersistentCartShareStub()
            ->getQuoteForPreview($resourceShareRequestTransfer);

        return $quoteResponseTransfer;
    }
}
