<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\Model;

use Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface;

class ShoppingListStorage implements ShoppingListStorageInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface
     */
    protected $shoppingListStorageRepository;

    /**
     * @param \Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface $shoppingListStorageRepository
     */
    public function __construct(ShoppingListStorageRepositoryInterface $shoppingListStorageRepository)
    {
        $this->shoppingListStorageRepository = $shoppingListStorageRepository;
    }

    /**
     * @param int[] $shoppingListIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByShoppingListIds(array $shoppingListIds): array
    {
        return $this->shoppingListStorageRepository->getCustomerReferencesByShoppingListIds($shoppingListIds);
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        return $this->shoppingListStorageRepository->getCustomerReferencesByCompanyBusinessUnitIds($companyBusinessUnitIds);
    }

    /**
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array
    {
        return $this->shoppingListStorageRepository->getCustomerReferencesByCompanyUserIds($companyUserIds);
    }
}
