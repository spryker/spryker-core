<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Persistence\Propel\Mapper;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess;
use Propel\Runtime\Collection\ObjectCollection;

interface CustomerAccessMapperInterface
{
    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess $customerAccessEntity
     * @param \Generated\Shared\Transfer\ContentTypeAccessTransfer $contentTypeAccessTransfer
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer
     */
    public function mapCustomerAccessEntityToContentTypeAccessTransfer(
        SpyUnauthenticatedCustomerAccess $customerAccessEntity,
        ContentTypeAccessTransfer $contentTypeAccessTransfer
    ): ContentTypeAccessTransfer;

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $customerAccessEntities
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function mapEntitiesToCustomerAccessTransfer(
        ObjectCollection $customerAccessEntities,
        CustomerAccessTransfer $customerAccessTransfer
    ): CustomerAccessTransfer;

    /**
     * @param \Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess $customerAccessEntity
     * @param \Generated\Shared\Transfer\CustomerAccessTransfer $customerAccessTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function mapEntityToCustomerAccessTransfer(
        SpyUnauthenticatedCustomerAccess $customerAccessEntity,
        CustomerAccessTransfer $customerAccessTransfer
    ): CustomerAccessTransfer;
}
