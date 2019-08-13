<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\UrlIdentifiersRestApi\Processor\UrlIdentifier\Mapper;

use Generated\Shared\Transfer\ResourceIdentifierTransfer;
use Generated\Shared\Transfer\RestUrlIdentifiersAttributesTransfer;

class UrlIdentifierMapper implements UrlIdentifierMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\ResourceIdentifierTransfer $resourceIdentifierTransfer
     * @param \Generated\Shared\Transfer\RestUrlIdentifiersAttributesTransfer $restUrlIdentifiersAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestUrlIdentifiersAttributesTransfer
     */
    public function mapResourceIdentifierTransferToRestUrlIdentifiersAttributesTransfer(
        ResourceIdentifierTransfer $resourceIdentifierTransfer,
        RestUrlIdentifiersAttributesTransfer $restUrlIdentifiersAttributesTransfer
    ): RestUrlIdentifiersAttributesTransfer {
        return $restUrlIdentifiersAttributesTransfer->fromArray($resourceIdentifierTransfer->toArray(), true);
    }
}
