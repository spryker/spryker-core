<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Persistence;

interface CmsBlockProductConnectorRepositoryInterface
{
    /**
     * @param int $idLocale
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer[]
     */
    public function getAssignedProductAbstracts(int $idLocale, int $idCmsBlock): array;

    /**
     * @param int $idCmsBlock
     *
     * @return int[]
     */
    public function getAssignedProductAbstractIds(int $idCmsBlock): array;
}
