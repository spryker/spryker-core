<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Sales\Communication\Form\DataProvider;

use Generated\Shared\Transfer\CommentTransfer;

class CommentFormDataProvider
{

    /**
     * @param int $idSalesOrder
     *
     * @return array
     */
    public function getData($idSalesOrder)
    {
        return [
            CommentTransfer::MESSAGE => '',
            CommentTransfer::FK_SALES_ORDER => $idSalesOrder,
        ];
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

}
