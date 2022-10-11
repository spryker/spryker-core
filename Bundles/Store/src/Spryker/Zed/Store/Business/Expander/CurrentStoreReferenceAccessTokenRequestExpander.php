<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Store\Business\Expander;

use Generated\Shared\Transfer\AccessTokenRequestOptionsTransfer;
use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Spryker\Zed\Store\Business\Model\StoreReaderInterface;

class CurrentStoreReferenceAccessTokenRequestExpander implements CurrentStoreReferenceAccessTokenRequestExpanderInterface
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
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expand(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer
    {
        if ($this->isStoreReferenceInOptions($accessTokenRequestTransfer)) {
            return $accessTokenRequestTransfer;
        }

        $storeTransfer = $this->storeReader->getCurrentStore();

        $accessTokenRequestOptionsTransfer = $accessTokenRequestTransfer->getAccessTokenRequestOptions();
        if ($accessTokenRequestOptionsTransfer === null) {
            $accessTokenRequestOptionsTransfer = new AccessTokenRequestOptionsTransfer();
        }

        $accessTokenRequestOptionsTransfer->setStoreReference($storeTransfer->getStoreReferenceOrFail());
        $accessTokenRequestTransfer->setAccessTokenRequestOptions($accessTokenRequestOptionsTransfer);

        return $accessTokenRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return bool
     */
    protected function isStoreReferenceInOptions(AccessTokenRequestTransfer $accessTokenRequestTransfer): bool
    {
        return $accessTokenRequestTransfer->getAccessTokenRequestOptions()
            && $accessTokenRequestTransfer->getAccessTokenRequestOptions()->getStoreReference();
    }
}
