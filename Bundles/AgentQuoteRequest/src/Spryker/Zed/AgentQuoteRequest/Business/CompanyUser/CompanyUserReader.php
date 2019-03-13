<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AgentQuoteRequest\Business\CompanyUser;

use ArrayObject;
use Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CompanyUserQueryTransfer;
use Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestRepositoryInterface;

class CompanyUserReader implements CompanyUserReaderInterface
{
    /**
     * @var \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestRepositoryInterface
     */
    protected $agentQuoteRequestRepository;

    /**
     * @param \Spryker\Zed\AgentQuoteRequest\Persistence\AgentQuoteRequestRepositoryInterface $agentQuoteRequestRepository
     */
    public function __construct(AgentQuoteRequestRepositoryInterface $agentQuoteRequestRepository)
    {
        $this->agentQuoteRequestRepository = $agentQuoteRequestRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserQueryTransfer $companyUserQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserAutocompleteResponseTransfer
     */
    public function findCompanyUsersByQuery(CompanyUserQueryTransfer $companyUserQueryTransfer): CompanyUserAutocompleteResponseTransfer
    {
        $companyUsers = $this->agentQuoteRequestRepository->findCompanyUsersByQuery($companyUserQueryTransfer);

        return (new CompanyUserAutocompleteResponseTransfer())
            ->setCompanyUsers(new ArrayObject($companyUsers));
    }
}
