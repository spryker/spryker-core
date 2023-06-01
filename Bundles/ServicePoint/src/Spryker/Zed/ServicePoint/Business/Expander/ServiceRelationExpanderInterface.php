<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use Generated\Shared\Transfer\ServicePointCollectionTransfer;

interface ServiceRelationExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointCollectionTransfer $servicePointCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointCollectionTransfer
     */
    public function expandServicePointCollectionWithServiceRelations(
        ServicePointCollectionTransfer $servicePointCollectionTransfer
    ): ServicePointCollectionTransfer;
}
