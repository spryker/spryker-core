<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Updater;

use Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer;

interface ServicePointServiceUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointServiceCollectionRequestTransfer $servicePointServiceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointServiceCollectionResponseTransfer
     */
    public function updateServicePointServiceCollection(
        ServicePointServiceCollectionRequestTransfer $servicePointServiceCollectionRequestTransfer
    ): ServicePointServiceCollectionResponseTransfer;
}
