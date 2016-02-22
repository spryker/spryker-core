<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCartConnector\Business;

use Generated\Shared\Transfer\ChangeTransfer;

interface ProductCartConnectorFacadeInterface
{

    /**
     * @param \Generated\Shared\Transfer\ChangeTransfer $change
     *
     * @return \Generated\Shared\Transfer\ChangeTransfer
     */
    public function expandItems(ChangeTransfer $change);

}
