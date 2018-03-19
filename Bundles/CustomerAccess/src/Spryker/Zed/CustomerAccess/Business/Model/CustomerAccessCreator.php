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
     * @param bool $canAccess
     *
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function createCustomerAccess($contentType, $canAccess)
    {
        $customerAccess = new SpyUnauthenticatedCustomerAccess();
        $customerAccess->setContentType($contentType);
        $customerAccess->setCanAccess($canAccess);

        $customerAccess->save();

        $contentTypeAccess = (new ContentTypeAccessTransfer())->setCanAccess($canAccess)->setContentType($contentType);

        return (new CustomerAccessTransfer())->addContentTypeAccess($contentTypeAccess);
    }
}
