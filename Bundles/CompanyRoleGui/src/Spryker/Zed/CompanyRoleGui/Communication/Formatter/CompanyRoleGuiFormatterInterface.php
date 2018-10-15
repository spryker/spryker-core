<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Formatter;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;

interface CompanyRoleGuiFormatterInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyRoleCollectionTransfer $companyRoleCollectionTransfer
     *
     * @return string
     */
    public function formatCompanyRoleNames(CompanyRoleCollectionTransfer $companyRoleCollectionTransfer): string;
}
