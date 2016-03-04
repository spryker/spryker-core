<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
