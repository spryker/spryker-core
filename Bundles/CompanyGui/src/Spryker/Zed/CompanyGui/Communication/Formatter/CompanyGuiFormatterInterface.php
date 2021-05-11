<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyGui\Communication\Formatter;

use Generated\Shared\Transfer\CompanyCollectionTransfer;

interface CompanyGuiFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyCollectionTransfer $companyCollectionTransfer
     *
     * @return array
     */
    public function formatCompanyCollectionToSuggestions(CompanyCollectionTransfer $companyCollectionTransfer): array;
}
