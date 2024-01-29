<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Creator;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer;

interface DynamicEntityCreatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @throws \Spryker\Zed\DynamicEntity\Business\Exception\DynamicEntityConfigurationNotFoundException
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionResponseTransfer
     */
    public function create(DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer): DynamicEntityCollectionResponseTransfer;
}
