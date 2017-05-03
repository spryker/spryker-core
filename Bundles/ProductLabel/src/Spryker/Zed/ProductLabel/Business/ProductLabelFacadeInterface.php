<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business;

use Generated\Shared\Transfer\ProductLabelTransfer;

interface ProductLabelFacadeInterface
{

    /**
     * @param int $idProductLabel
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function readLabel($idProductLabel);

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductLabelTransfer $productLabelTransfer
     *
     * @return void
     */
    public function createLabel(ProductLabelTransfer $productLabelTransfer);

}
