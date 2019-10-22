<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlot\Business\Reader;

use Generated\Shared\Transfer\CmsSlotTemplateTransfer;

interface CmsSlotTemplateReaderInterface
{
    /**
     * @param int $idCmsSlotTemplate
     *
     * @return \Generated\Shared\Transfer\CmsSlotTemplateTransfer
     */
    public function getTemplateById(int $idCmsSlotTemplate): CmsSlotTemplateTransfer;
}
