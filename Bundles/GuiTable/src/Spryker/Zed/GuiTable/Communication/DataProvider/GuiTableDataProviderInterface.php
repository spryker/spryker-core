<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\GuiTable\Communication\DataProvider;

use Generated\Shared\Transfer\GuiTableDataRequestTransfer;

interface GuiTableDataProviderInterface
{
    /**
     * @param \Generated\Shared\Transfer\GuiTableDataRequestTransfer $guiTableDataRequestTransfer
     *
     * @return \Generated\Shared\Transfer\GuiTableDataResponseTransfer|mixed
     */
    public function getData(GuiTableDataRequestTransfer $guiTableDataRequestTransfer);
}
