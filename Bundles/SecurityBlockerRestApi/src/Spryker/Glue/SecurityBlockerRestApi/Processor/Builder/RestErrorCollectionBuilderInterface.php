<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SecurityBlockerRestApi\Processor\Builder;

use Generated\Shared\Transfer\RestErrorCollectionTransfer;
use Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer;

interface RestErrorCollectionBuilderInterface
{
    /**
     * @param \Generated\Shared\Transfer\SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\RestErrorCollectionTransfer
     */
    public function createRestErrorCollectionTransfer(
        SecurityCheckAuthResponseTransfer $securityCheckAuthResponseTransfer,
        string $localeName
    ): RestErrorCollectionTransfer;
}
