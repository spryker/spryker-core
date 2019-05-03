<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareResponseTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\PersistentCartShare\PersistentCartShareFactory getFactory()
 */
class PersistentCartShareClient extends AbstractClient implements PersistentCartShareClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $resourceShareUuid
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getQuoteByResourceShareUuid(string $resourceShareUuid): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteReader()
            ->getQuoteByResourceShareUuid($resourceShareUuid);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string[][]
     */
    public function getCartShareOptions(): array
    {
        return $this->getFactory()
            ->createCartShareOptionReader()
            ->getCartShareOptions();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int $idQuote
     * @param string $shareOption
     *
     * @return \Generated\Shared\Transfer\ResourceShareResponseTransfer
     */
    public function generateCartResourceShare(int $idQuote, string $shareOption): ResourceShareResponseTransfer
    {
        $resourceShareRequestTransfer = $this->getFactory()
            ->createResourceShareRequestBuilder()
            ->buildResourceShareRequest($idQuote, $shareOption);

        return $this->getFactory()
            ->getResourceShareClient()
            ->generateResourceShare($resourceShareRequestTransfer);
    }
}
