<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Dependency;

interface CmsSlotGuiToCmsSlotFacadeInterface
{
    /**
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function activateByIdCmsSlot(int $idCmsSlot): void;

    /**
     * @param int $idCmsSlot
     *
     * @return void
     */
    public function deactivateByIdCmsSlot(int $idCmsSlot): void;
}
