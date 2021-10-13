<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Acl\Business\Writer;

use Generated\Shared\Transfer\GroupTransfer;

interface GroupWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\GroupTransfer $groupTransfer
     *
     * @return \Generated\Shared\Transfer\GroupTransfer
     */
    public function createGroup(GroupTransfer $groupTransfer): GroupTransfer;
}
