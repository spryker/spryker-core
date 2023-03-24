<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Resolver;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface;

interface PickingListUpdateStrategyResolverInterface
{
    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Spryker\Glue\PickingListsBackendApi\Processor\Strategy\PickingListUpdateStrategyInterface
     */
    public function resolve(GlueRequestTransfer $glueRequestTransfer): PickingListUpdateStrategyInterface;
}
