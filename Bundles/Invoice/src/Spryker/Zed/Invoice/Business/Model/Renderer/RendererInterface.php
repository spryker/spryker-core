<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Invoice\Business\Model\Renderer;

use Generated\Shared\Transfer\OrderTransfer;

interface RendererInterface
{
    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function render(OrderTransfer $orderTransfer);
}
