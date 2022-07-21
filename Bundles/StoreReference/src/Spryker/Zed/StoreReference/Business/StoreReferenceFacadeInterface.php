<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\StoreReference\Business;

use Generated\Shared\Transfer\AccessTokenRequestTransfer;
use Generated\Shared\Transfer\StoreTransfer;

interface StoreReferenceFacadeInterface
{
    /**
     * Specification:
     * - Finds Store by storeReference.
     * - Returns StoreTransfer if Store has provided storeReference, otherwise throws the exception.
     * - Expands StoreTransfer before being returned.
     *
     * @api
     *
     * @param string $storeReference
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreReference(string $storeReference): StoreTransfer;

    /**
     * Specification:
     * - Finds Store by storeName.
     * - Returns StoreTransfer if Store has provided storeReference, otherwise throws the exception.
     * - Expands StoreTransfer before being returned.
     *
     * @api
     *
     * @param string $storeName
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getStoreByStoreName(string $storeName): StoreTransfer;

    /**
     * Specification:
     * - Gets current store.
     * - Expands StoreTransfer with storeReference before being returned.
     *
     * @api
     *
     * @throws \Spryker\Zed\StoreReference\Business\Exception\StoreReferenceNotFoundException
     *
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    public function getCurrentStore(): StoreTransfer;

    /**
     * Specification:
     * - Finds a store reference for currently selected store.
     * - Expands `AccessTokenRequest.accessTokenRequestOptions` with found store reference.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AccessTokenRequestTransfer $accessTokenRequestTransfer
     *
     * @return \Generated\Shared\Transfer\AccessTokenRequestTransfer
     */
    public function expandAccessTokenRequest(AccessTokenRequestTransfer $accessTokenRequestTransfer): AccessTokenRequestTransfer;
}
