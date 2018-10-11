<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Business\CustomerAccessPublisher;

use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface;
use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface;

class CustomerAccessStorage implements CustomerAccessStorageInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface
     */
    protected $customerAccessStorageRepository;

    /**
     * @var \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface
     */
    protected $customerAccessStorageEntityManager;

    /**
     * @param \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface $customerAccessStorageRepository
     * @param \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface $customerAccessStorageEntityManager
     */
    public function __construct(
        CustomerAccessStorageRepositoryInterface $customerAccessStorageRepository,
        CustomerAccessStorageEntityManagerInterface $customerAccessStorageEntityManager
    ) {
        $this->customerAccessStorageRepository = $customerAccessStorageRepository;
        $this->customerAccessStorageEntityManager = $customerAccessStorageEntityManager;
    }

    /**
     * @return void
     */
    public function publish(): void
    {
        $this->customerAccessStorageEntityManager->storeData($this->customerAccessStorageRepository->getUnauthenticatedCustomerAccess());
    }
}
