<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ServicePoint\Business\Expander;

use Generated\Shared\Transfer\ServicePointAddressCollectionTransfer;

interface ServicePointExpanderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ServicePointAddressCollectionTransfer
     */
    public function expandServicePointAddressCollectionWithServicePointIds(
        ServicePointAddressCollectionTransfer $servicePointAddressCollectionTransfer
    ): ServicePointAddressCollectionTransfer;
}
