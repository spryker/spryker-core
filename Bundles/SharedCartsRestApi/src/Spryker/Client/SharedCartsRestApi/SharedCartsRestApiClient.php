<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\SharedCartsRestApi;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiFactory getFactory()
 */
class SharedCartsRestApiClient extends AbstractClient implements SharedCartsRestApiClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        return $this->getFactory()->createSharedCartsRestApiStub()->getSharedCartsByCartUuid($quoteTransfer);
    }
}
