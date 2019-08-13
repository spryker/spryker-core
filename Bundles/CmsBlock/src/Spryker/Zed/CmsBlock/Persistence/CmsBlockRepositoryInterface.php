<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlock\Persistence;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockRepositoryInterface
{
    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer|null
     */
    public function findCmsBlockByName(string $name): ?CmsBlockTransfer;

    /**
     * @param string $key
     *
     * @return bool
     */
    public function hasKey(string $key): bool;
}
