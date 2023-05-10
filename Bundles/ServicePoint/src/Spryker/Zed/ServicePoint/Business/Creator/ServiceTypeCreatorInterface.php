<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Creator;

use Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer;

interface ServiceTypeCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServiceTypeCollectionRequestTransfer $serviceTypeCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ServiceTypeCollectionResponseTransfer
     */
    public function createServiceTypeCollection(
        ServiceTypeCollectionRequestTransfer $serviceTypeCollectionRequestTransfer
    ): ServiceTypeCollectionResponseTransfer;
}
