<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CmsBlock;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockClientInterface
{

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     * @param string $localName
     *
     * @return array
     */
    public function findBlockByName(CmsBlockTransfer $cmsBlockTransfer, $localName);

    /**
     * @param array $options
     * @param string $localName
     *
     * @return array
     */
    public function findBlocksByOptions(array $options, $localName);

}
