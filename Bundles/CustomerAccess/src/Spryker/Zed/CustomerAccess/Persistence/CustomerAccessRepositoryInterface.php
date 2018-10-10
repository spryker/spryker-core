<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;

/**
 * @method \Spryker\Zed\CustomerAccess\Persistence\CustomerAccessPersistenceFactory getFactory()
 */
interface CustomerAccessRepositoryInterface
{
    /**
     * Specification:
     * - Returns customer access based on content type or null
     *
     * @api
     *
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer|null
     */
    public function findCustomerAccessByContentType($contentType): ?ContentTypeAccessTransfer;

    /**
     * Specification:
     * - Returns list of content type accesses that granted for unauthenticated customer
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getUnrestrictedContentTypes(): CustomerAccessTransfer;

    /**
     * Specification:
     * - Returns list of accesses for content that unauthenticated customer can have
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getAllContentTypes(): CustomerAccessTransfer;

    /**
     * Specification:
     * - Returns list of accesses for content that unauthenticated customer can not have
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function getRestrictedContentTypes(): CustomerAccessTransfer;
}
