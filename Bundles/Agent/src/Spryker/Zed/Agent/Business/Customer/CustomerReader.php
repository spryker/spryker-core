<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Agent\Business\Customer;

use Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer;
use Generated\Shared\Transfer\CustomerQueryTransfer;
use Spryker\Zed\Agent\Persistence\AgentRepositoryInterface;

class CustomerReader implements CustomerReaderInterface
{
    /**
     * @var \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface
     */
    protected $agentRepository;

    /**
     * @param \Spryker\Zed\Agent\Persistence\AgentRepositoryInterface $agentRepository
     */
    public function __construct(AgentRepositoryInterface $agentRepository)
    {
        $this->agentRepository = $agentRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerQueryTransfer $customerQueryTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerAutocompleteResponseTransfer
     */
    public function findCustomersByQuery(CustomerQueryTransfer $customerQueryTransfer): CustomerAutocompleteResponseTransfer
    {
        return $this->agentRepository->findCustomersByQuery(
            $customerQueryTransfer->getQuery(),
            $customerQueryTransfer->getLimit(),
            $customerQueryTransfer->getOffset()
        );
    }
}
