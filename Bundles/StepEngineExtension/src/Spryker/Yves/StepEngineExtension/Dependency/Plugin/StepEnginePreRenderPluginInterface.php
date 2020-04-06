<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StepEngineExtension\Dependency\Plugin;

use Generated\Shared\Transfer\QuoteTransfer;

/**
 * Provides preparation of quote transfer for each step of checkout process before template rendering.
 */
interface StepEnginePreRenderPluginInterface
{
    /**
     * Specifications:
     * - Prepares quote transfer for each step of checkout process before template rendering.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function execute(QuoteTransfer $quoteTransfer): QuoteTransfer;
}
