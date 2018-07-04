<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGuiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ButtonTransfer;

interface ProductListTableActionExpanderInterface
{
    /**
     * Specification:
     * - Prepares ButtonTransfer for using in actions list
     *
     * @api
     *
     * @param array $company
     *
     * @return \Generated\Shared\Transfer\ButtonTransfer
     */
    public function prepareButton(array $company): ButtonTransfer;
}
