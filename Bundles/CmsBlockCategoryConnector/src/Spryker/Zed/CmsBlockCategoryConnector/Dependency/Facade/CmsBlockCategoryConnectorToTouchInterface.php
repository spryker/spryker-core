<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockCategoryConnector\Dependency\Facade;

interface CmsBlockCategoryConnectorToTouchInterface
{

    /**
     * @param string $itemType
     * @param int $idCmsBlock
     *
     * @return bool
     */
    public function touchActive($itemType, $idCmsBlock);

    /**
     * @param string $itemType
     * @param int $idCmsBlock
     *
     * @return bool
     */
    public function touchDeleted($itemType, $idCmsBlock);

}
