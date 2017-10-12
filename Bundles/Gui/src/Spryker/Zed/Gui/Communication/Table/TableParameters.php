<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Gui\Communication\Table;

use Generated\Shared\Transfer\DataTablesTransfer;
use Symfony\Component\HttpFoundation\Request;

class TableParameters
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\DataTablesTransfer
     */
    public static function getTableParameters(Request $request)
    {
        $getParameters = $request->query->all();

        return (new DataTablesTransfer())->fromArray($getParameters, true);
    }
}
