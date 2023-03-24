<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PickingList\Business\StatusGenerator;

use Generated\Shared\Transfer\PickingListTransfer;

interface PickingListStatusGeneratorInterface
{
    /**
     * @param \Generated\Shared\Transfer\PickingListTransfer $pickingListTransfer
     *
     * @return string
     */
    public function generatePickingListStatus(
        PickingListTransfer $pickingListTransfer
    ): string;
}
