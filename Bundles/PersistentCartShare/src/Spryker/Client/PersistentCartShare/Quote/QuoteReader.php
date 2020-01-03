<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PersistentCartShare\Quote;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Generated\Shared\Transfer\ResourceShareRequestTransfer;
use Spryker\Client\PersistentCartShare\Zed\PersistentCartShareStubInterface;

class QuoteReader implements QuoteReaderInterface
{
    /**
     * @var \Spryker\Client\PersistentCartShare\Zed\PersistentCartShareStubInterface
     */
    protected $zedPersistentCartShareStub;

    /**
     * @param \Spryker\Client\PersistentCartShare\Zed\PersistentCartShareStubInterface $zedPersistentCartShareStub
     */
    public function __construct(PersistentCartShareStubInterface $zedPersistentCartShareStub)
    {
        $this->zedPersistentCartShareStub = $zedPersistentCartShareStub;
    }

    /**
     * @param \Generated\Shared\Transfer\ResourceShareRequestTransfer $resourceShareRequestTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteResponseTransfer
     */
    public function getPreviewQuoteResourceShare(ResourceShareRequestTransfer $resourceShareRequestTransfer): QuoteResponseTransfer
    {
        return $this->zedPersistentCartShareStub->getPreviewQuoteResourceShare($resourceShareRequestTransfer);
    }
}
