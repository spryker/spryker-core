<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Formatter;

use Generated\Shared\Transfer\CompanyTransfer;

class CompanyNameFormatter implements CompanyNameFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     *
     * @return string
     */
    public function formatName(CompanyTransfer $companyTransfer): string
    {
        return sprintf(
            '%s (id: %s)',
            $companyTransfer->getNameOrFail(),
            $companyTransfer->getIdCompanyOrFail(),
        );
    }
}
