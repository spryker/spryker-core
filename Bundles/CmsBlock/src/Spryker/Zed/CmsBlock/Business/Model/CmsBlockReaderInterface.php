<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockReaderInterface
{
    /**
     * @param int $idCmsBlock
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockById(int $idCmsBlock): ?CmsBlockTransfer;
}
