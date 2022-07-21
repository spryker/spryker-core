<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueBackendApiApplication\Expander;

use Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer;

interface ContextExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer
     *
     * @return \Generated\Shared\Transfer\ApiApplicationSchemaContextTransfer
     */
    public function expand(ApiApplicationSchemaContextTransfer $apiApplicationSchemaContextTransfer): ApiApplicationSchemaContextTransfer;
}
