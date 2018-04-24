<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

use Generated\Shared\Transfer\ContentTypeAccessTransfer;
use Generated\Shared\Transfer\CustomerAccessTransfer;
use Orm\Zed\CustomerAccess\Persistence\SpyUnauthenticatedCustomerAccess;

class CustomerAccessCreator implements CustomerAccessCreatorInterface
{
    /**
     * @param string $contentType
     * @param bool $hasAccess
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess($contentType, $hasAccess)
    {
        $customerAccess = new SpyUnauthenticatedCustomerAccess();
        $customerAccess->setContentType($contentType);
        $customerAccess->setHasAccess($hasAccess);

        $customerAccess->save();

        $contentTypeAccess = (new ContentTypeAccessTransfer())->setHasAccess($hasAccess)->setContentType($contentType);

        return (new CustomerAccessTransfer())->addContentTypeAccess($contentTypeAccess);
    }
}
