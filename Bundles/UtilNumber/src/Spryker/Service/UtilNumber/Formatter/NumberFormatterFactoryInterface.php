<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilNumber\Formatter;

use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use NumberFormatter as IntlNumberFormatter;

interface NumberFormatterFactoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\NumberFormatFilterTransfer $numberFormatFilterTransfer
     *
     * @return \NumberFormatter
     */
    public function createIntlNumberFormatter(NumberFormatFilterTransfer $numberFormatFilterTransfer): IntlNumberFormatter;
}
