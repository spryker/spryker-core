<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Checkout\Dependency\Plugin\Form;

use Generated\Shared\Transfer\QuoteTransfer;

interface SubFormFilterPluginInterface
{
    /**
     * Specification:
     * - Provides an array with valid form names listed
     *
     * @api
     *
     * @example ['subForm1', 'subForm2']
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    public function getValidFormNames(QuoteTransfer $quoteTransfer);
}
