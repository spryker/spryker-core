<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Strategy;

use Generated\Shared\Transfer\GlueRequestTransfer;

class StartPickingPickingListUpdateStrategy extends AbstractPickingListUpdateStrategy
{
    /**
     * @var string
     */
    protected const ATTRIBUTE_ACTION = 'startPicking';

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return bool
     */
    public function isApplicable(GlueRequestTransfer $glueRequestTransfer): bool
    {
        /** @var \Generated\Shared\Transfer\ApiPickingListsRequestAttributesTransfer $apiPickingListsRequestAttributesTransfer */
        $apiPickingListsRequestAttributesTransfer = $glueRequestTransfer->getResourceOrFail()->getAttributesOrFail();

        return $apiPickingListsRequestAttributesTransfer->getAction() === static::ATTRIBUTE_ACTION;
    }
}
