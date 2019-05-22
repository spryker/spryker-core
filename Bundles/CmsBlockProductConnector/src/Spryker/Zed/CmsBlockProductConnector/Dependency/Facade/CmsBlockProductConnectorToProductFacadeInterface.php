<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Dependency\Facade;

use Generated\Shared\Transfer\PaginationTransfer;

interface CmsBlockProductConnectorToProductFacadeInterface
{
    /**
     * @param string $suggestion
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function suggestProductAbstractTransfersPaginated(string $suggestion, PaginationTransfer $paginationTransfer): array;
}
