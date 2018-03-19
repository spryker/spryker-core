<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CustomerAccessStorage;

use Generated\Shared\Transfer\CustomerAccessTransfer;

interface CustomerAccessStorageClientInterface
{
    /**
     * Specification:
     * - Returns CustomerAccessTransfer containing ContentTypeAccess array with the content types that an unauthenticated customer can see
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnauthenticatedCustomerAccess(): CustomerAccessTransfer;

    /**
     * Specification:
     * - Returns CustomerAccessTransfer containing ContentTypeAccess array with the content types that an authenticated customer can see
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAuthenticatedCustomerAccess(): CustomerAccessTransfer;
}
