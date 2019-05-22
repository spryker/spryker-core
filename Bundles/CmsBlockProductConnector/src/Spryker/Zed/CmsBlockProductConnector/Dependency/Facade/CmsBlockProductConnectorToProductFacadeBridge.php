<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Dependency\Facade;

use Generated\Shared\Transfer\PaginationTransfer;

class CmsBlockProductConnectorToProductFacadeBridge implements CmsBlockProductConnectorToProductFacadeInterface
{
    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct($productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param string $suggestion
     * @param \Generated\Shared\Transfer\PaginationTransfer $paginationTransfer
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function suggestProductAbstractTransfersPaginated(string $suggestion, PaginationTransfer $paginationTransfer): array
    {
        return $this->productFacade->suggestProductAbstractTransfersPaginated($suggestion, $paginationTransfer);
    }
}
