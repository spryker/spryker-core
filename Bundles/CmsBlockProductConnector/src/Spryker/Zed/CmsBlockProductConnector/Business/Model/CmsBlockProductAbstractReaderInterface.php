<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockProductConnector\Business\Model;

use Generated\Shared\Transfer\CmsBlockTransfer;

interface CmsBlockProductAbstractReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsBlockTransfer $cmsBlockTransfer
     *
     * @return \Generated\Shared\Transfer\CmsBlockTransfer
     */
    public function hydrateProductRelations(CmsBlockTransfer $cmsBlockTransfer);

    /**
     * @param int $idCmsBlock
     * @param int $idLocale
     *
     * @return array<string>
     */
    public function getProductAbstractRenderedList($idCmsBlock, $idLocale);

    /**
     * @param int $idProductAbstract
     *
     * @return array<string>
     */
    public function getCmsBlockRenderedList($idProductAbstract);
}
