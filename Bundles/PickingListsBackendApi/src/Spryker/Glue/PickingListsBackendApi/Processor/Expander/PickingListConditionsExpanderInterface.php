<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\PickingListsBackendApi\Processor\Expander;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\PickingListConditionsTransfer;

interface PickingListConditionsExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListConditionsTransfer $pickingListConditionsTransfer
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return \Generated\Shared\Transfer\PickingListConditionsTransfer
     */
    public function expandWithRequestData(
        PickingListConditionsTransfer $pickingListConditionsTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): PickingListConditionsTransfer;
}
