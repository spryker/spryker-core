<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Cms\Dependency;

use Generated\Shared\Transfer\CmsVersionTransfer;

interface CmsVersionTransferExpanderPlugin
{

    public function expandTransfer(CmsVersionTransfer $cmsVersionTransfer);

}
