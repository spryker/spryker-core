<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Persistence;

use Generated\Shared\Transfer\CompanyUserQueryTransfer;

interface AgentQuoteRequestRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $companyUserQueryTransfer
     *
     * @return array
     */
    public function findCompanyUsersByQuery(CompanyUserQueryTransfer $companyUserQueryTransfer): array;
}
