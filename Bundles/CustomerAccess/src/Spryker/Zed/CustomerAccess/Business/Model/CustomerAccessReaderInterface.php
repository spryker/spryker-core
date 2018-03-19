<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccess\Business\Model;

interface CustomerAccessReaderInterface
{
    /**
     * @param string $contentType
     *
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer|null
     */
    public function findCustomerAccessByContentType($contentType);

    /**
     * @return \Generated\Shared\Transfer\CustomerAccessTransfer
     */
    public function findUnauthenticatedCustomerAccess();

    /**
     * @return \Generated\Shared\Transfer\ContentTypeAccessTransfer[]
     */
    public function findAllContentTypes();
}
