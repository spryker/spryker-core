<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\QuickOrderExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuickOrderTransfer;

interface QuickOrderValidatorPluginInterface
{
    /**
     * Specification:
     * - Validate QuickOrderTransfer.
     * - Executed on quick order page.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderTransfer $quickOrderTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderTransfer
     */
    public function validate(QuickOrderTransfer $quickOrderTransfer): QuickOrderTransfer;
}
