<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Business\Version\Migration;

use Generated\Shared\Transfer\CmsVersionDataTransfer;

interface MigrationInterface
{
    /**
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $originVersionDataTransfer
     * @param \Generated\Shared\Transfer\CmsVersionDataTransfer $targetVersionDataTransfer
     *
     * @return void
     */
    public function migrate(
        CmsVersionDataTransfer $originVersionDataTransfer,
        CmsVersionDataTransfer $targetVersionDataTransfer
    );
}
