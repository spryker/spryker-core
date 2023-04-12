<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Updater;

use Generated\Shared\Transfer\ServicePointTransfer;

interface ServicePointStoreRelationUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointTransfer $servicePointTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointTransfer
     */
    public function updateServicePointStoreRelations(ServicePointTransfer $servicePointTransfer): ServicePointTransfer;
}
