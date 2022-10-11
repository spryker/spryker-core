<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Expander;

use Generated\Shared\Transfer\MessageAttributesTransfer;
use Spryker\Zed\Store\Business\Model\Exception\StoreNotFoundException;
use Spryker\Zed\Store\Business\Model\StoreReaderInterface;

class CurrentStoreReferenceMessageAttributesExpander implements CurrentStoreReferenceMessageAttributesExpanderInterface
{
    /**
     * @var \Spryker\Zed\Store\Business\Model\StoreReaderInterface
     */
    protected $storeReader;

    /**
     * @param \Spryker\Zed\Store\Business\Model\StoreReaderInterface $storeReader
     */
    public function __construct(StoreReaderInterface $storeReader)
    {
        $this->storeReader = $storeReader;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageAttributesTransfer $messageAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\MessageAttributesTransfer
     */
    public function expand(
        MessageAttributesTransfer $messageAttributesTransfer
    ): MessageAttributesTransfer {
        try {
            $storeTransfer = $this->storeReader->getCurrentStore();
        } catch (StoreNotFoundException $exception) {
            return $messageAttributesTransfer;
        }

        $storeReference = $storeTransfer->getStoreReference();
        $messageAttributesTransfer->setStoreReference($storeReference);

        $emitter = $messageAttributesTransfer->getEmitter() ?? $storeReference;
        $messageAttributesTransfer->setEmitter($emitter);

        return $messageAttributesTransfer;
    }
}
