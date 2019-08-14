<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlsRestApi\Processor\Url\Mapper;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestUrlsAttributesTransfer;

interface UrlMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceIdentifierTransfer $resourceIdentifierTransfer
     * @param \Generated\Shared\Transfer\RestUrlsAttributesTransfer $restUrlsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlsAttributesTransfer
     */
    public function mapResourceIdentifierTransferToRestUrlsAttributesTransfer(
        ResourceIdentifierTransfer $resourceIdentifierTransfer,
        RestUrlsAttributesTransfer $restUrlsAttributesTransfer
    ): RestUrlsAttributesTransfer;
}
