<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart;

use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\ShareDetailCollectionTransfer;
use Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface;

class SharedCartReader implements SharedCartReaderInterface
{
    /**
     * @var \Spryker\Client\SharedCartsRestApi\Dependency\Client\SharedCartsRestApiToZedRequestClientInterface
     */
    protected $sharedCartsRestApiClient;

    /**
     * @param \Spryker\Client\SharedCartsRestApi\SharedCartsRestApiClientInterface $sharedCartsRestApiClient
     */
    public function __construct(
        SharedCartsRestApiClientInterface $sharedCartsRestApiClient
    ) {
        $this->sharedCartsRestApiClient = $sharedCartsRestApiClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ShareDetailCollectionTransfer
     */
    public function getSharedCartsByCartUuid(QuoteTransfer $quoteTransfer): ShareDetailCollectionTransfer
    {
        return $this->sharedCartsRestApiClient->getSharedCartsByCartUuid(
            $quoteTransfer
        );
    }
}
