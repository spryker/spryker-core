<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteRequestAgent\Business\Reader;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserCriteriaTransfer;
use Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToCompanyUserFacadeInterface;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToCompanyUserFacadeInterface
     */
    protected $companyUserFacade;

    /**
     * @param \Spryker\Zed\QuoteRequestAgent\Dependency\Facade\QuoteRequestAgentToCompanyUserFacadeInterface $companyUserFacade
     */
    public function __construct(QuoteRequestAgentToCompanyUserFacadeInterface $companyUserFacade)
    {
        $this->companyUserFacade = $companyUserFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserCriteriaTransfer $companyUserCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function getCompanyUserCollectionByQuery(CompanyUserCriteriaTransfer $companyUserCriteriaTransfer): CompanyUserCollectionTransfer
    {
        return $this->companyUserFacade->getCompanyUserCollectionByQuery($companyUserCriteriaTransfer);
    }
}
