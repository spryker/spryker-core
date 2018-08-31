<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListStorage\Business\Model;

use Spryker\Zed\ShoppingListStorage\Persistence\ShoppingListStorageRepositoryInterface;

class ShoppingListStorageReader implements ShoppingListStorageReaderInterface
{
    protected const OWN_CUSTOMER_REFERENCES = 'ownCustomerReferences';
    protected const SHARED_WITH_COMPANY_USER_CUSTOMER_REFERENCES = 'sharedWithCompanyUserCustomerReferences';
    protected const SHARED_WITH_COMPANY_BUSINESS_UNIT_CUSTOMER_REFERENCES = 'sharedWithCompanyBusinessUnitCustomerReferences';

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
        $ownCustomerReferences = $this->shoppingListStorageRepository->getOwnCustomerReferencesByShoppingListIds($shoppingListIds);
        $sharedWithCompanyUserCustomerReferences = $this->shoppingListStorageRepository->getSharedWithCompanyUserCustomerReferencesByShoppingListIds($shoppingListIds);
        $sharedWithCompanyBusinessUnitCustomerReferences = $this->shoppingListStorageRepository->getSharedWithCompanyBusinessUnitCustomerReferencesByShoppingListIds($shoppingListIds);
        $customerReferences = array_merge($ownCustomerReferences, $sharedWithCompanyUserCustomerReferences, $sharedWithCompanyBusinessUnitCustomerReferences);

        return array_unique($customerReferences);
    }

    /**
     * @param int[] $companyBusinessUnitIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyBusinessUnitIds(array $companyBusinessUnitIds): array
    {
        $shoppingListIds = $this->shoppingListStorageRepository->getShoppingListIdsByCompanyBusinessUnitIds($companyBusinessUnitIds);

        return $this->getCustomerReferencesByShoppingListIds($shoppingListIds);
    }

    /**
     * @param int[] $companyUserIds
     *
     * @return string[]
     */
    public function getCustomerReferencesByCompanyUserIds(array $companyUserIds): array
    {
        $shoppingListIds = $this->shoppingListStorageRepository->getShoppingListIdsByCompanyUserIds($companyUserIds);

        return $this->getCustomerReferencesByShoppingListIds($shoppingListIds);
    }
}
