<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock\Storage;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockStorageInterface
{

    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param string $localeName
     *
     * @return array
     */
    public function getBlockByName(CmsBlockTransfer $cmsBlockTransfer, $localeName);

}
