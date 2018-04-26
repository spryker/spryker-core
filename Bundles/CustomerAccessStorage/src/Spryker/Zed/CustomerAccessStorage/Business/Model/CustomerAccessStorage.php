<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerAccessStorage\Business\Model;

use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface;
use Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface;

class CustomerAccessStorage implements CustomerAccessStorageInterface
{
    /**
     * @var \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface
     */
    protected $repository;

    /**
     * @var \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageRepositoryInterface $repository
     * @param \Spryker\Zed\CustomerAccessStorage\Persistence\CustomerAccessStorageEntityManagerInterface $entityManager
     */
    public function __construct(CustomerAccessStorageRepositoryInterface $repository, CustomerAccessStorageEntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * @return void
     */
    public function publish(): void
    {
        $this->entityManager->storeData($this->repository->getUnauthenticatedCustomerAccess());
    }
}
