<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Updater;

use Generated\Shared\Transfer\ServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceCollectionResponseTransfer;

interface ServiceUpdaterInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceCollectionRequestTransfer $serviceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceCollectionResponseTransfer
     */
    public function updateServiceCollection(
        ServiceCollectionRequestTransfer $serviceCollectionRequestTransfer
    ): ServiceCollectionResponseTransfer;
}
