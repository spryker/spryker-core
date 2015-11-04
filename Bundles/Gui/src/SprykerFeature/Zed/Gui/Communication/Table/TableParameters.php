<?php

namespace SprykerFeature\Zed\Gui\Communication\Table;

use Generated\Shared\Transfer\DataTablesTransfer;
use Symfony\Component\HttpFoundation\Request;

class TableParameters
{

    /**
     * @param Request $request
     *
     * @return DataTablesTransfer
     */
    public static function getTableParameters(Request $request)
    {
        $getParameters = $request->query->all();

        return (new DataTablesTransfer())->fromArray($getParameters, true);
    }

}
