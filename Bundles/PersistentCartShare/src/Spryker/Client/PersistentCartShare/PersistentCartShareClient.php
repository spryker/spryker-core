<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare;

use Generated\Shared\Transfer\QuoteResponseTransfer;
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
    public function getQuoteForPreview(string $resourceShareUuid): QuoteResponseTransfer
    {
        return $this->getFactory()
            ->createQuoteReader()
            ->getQuoteForPreview($resourceShareUuid);
    }
}
