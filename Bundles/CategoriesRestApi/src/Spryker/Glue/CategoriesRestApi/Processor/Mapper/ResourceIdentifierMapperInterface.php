<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;

interface ResourceIdentifierMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param \Generated\Shared\Transfer\ResourceIdentifierTransfer $resourceIdentifierTransfer
     *
     * @return \Generated\Shared\Transfer\ResourceIdentifierTransfer
     */
    public function mapUrlStorageTransferToResourceIdentifierTransfer(
        UrlStorageTransfer $urlStorageTransfer,
        ResourceIdentifierTransfer $resourceIdentifierTransfer
    ): ResourceIdentifierTransfer;
}
