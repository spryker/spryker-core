<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\GlueStorefrontApiApplicationAuthorizationConnector\Processor\ProtectedPathAuthorization\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

interface ProtectedPathAuthorizationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expandApiApplicationSchemaContext(
        ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
    ): ApiApplicationSchemaContextTransfer;
}
