<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Persistence;

use ArrayObject;

interface CmsRepositoryInterface
{
    /**
     * Specification:
     * - Retrieve stores related to cms page
     *
     * @param int $idCmsPage
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\StoreTransfer[]
     */
    public function getRelatedStoresByIdCmsPage(int $idCmsPage): ArrayObject;
}
