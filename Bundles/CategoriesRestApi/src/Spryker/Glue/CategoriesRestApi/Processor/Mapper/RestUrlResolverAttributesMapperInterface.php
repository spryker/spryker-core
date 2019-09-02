<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestUrlResolverAttributesTransfer;
use Generated\Shared\Transfer\UrlStorageTransfer;

interface RestUrlResolverAttributesMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\UrlStorageTransfer $urlStorageTransfer
     * @param \Generated\Shared\Transfer\RestUrlResolverAttributesTransfer $restUrlResolverAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlResolverAttributesTransfer
     */
    public function mapUrlStorageTransferToRestUrlResolverAttributesTransfer(
        UrlStorageTransfer $urlStorageTransfer,
        RestUrlResolverAttributesTransfer $restUrlResolverAttributesTransfer
    ): RestUrlResolverAttributesTransfer;
}
