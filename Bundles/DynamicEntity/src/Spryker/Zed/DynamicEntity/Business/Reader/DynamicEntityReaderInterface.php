<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntity\Business\Reader;

use Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer;
use Generated\Shared\Transfer\DynamicEntityCollectionTransfer;
use Generated\Shared\Transfer\DynamicEntityCriteriaTransfer;
use Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface;

interface DynamicEntityReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\DynamicEntityCollectionTransfer
     */
    public function getEntityCollection(DynamicEntityCriteriaTransfer $dynamicEntityCriteriaTransfer): DynamicEntityCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
     *
     * @return \Spryker\Zed\DynamicEntity\Business\Configuration\DynamicEntityConfigurationResponseInterface
     */
    public function getDynamicEntityConfigurationTransferTree(
        DynamicEntityCollectionRequestTransfer $dynamicEntityCollectionRequestTransfer
    ): DynamicEntityConfigurationResponseInterface;
}
