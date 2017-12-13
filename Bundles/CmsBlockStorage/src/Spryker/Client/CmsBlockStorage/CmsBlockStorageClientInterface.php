<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlockStorage;

interface CmsBlockStorageClientInterface
{

    /**
     * Specification:
     * - Returns cms block data from the storage with the given
     * block name
     *
     * @api
     *
     * @param string $blockKey
     *
     * @return \Generated\Shared\Transfer\SpyCmsBlockTransfer
     */
    public function getBlockByKey($blockKey);

}
