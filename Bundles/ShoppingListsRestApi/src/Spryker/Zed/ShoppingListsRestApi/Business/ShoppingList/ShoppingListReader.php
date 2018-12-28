<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use ArrayObject;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\ShoppingListsRestApi\Business\Customer\CustomerReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListReader implements ShoppingListReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListMapperInterface
     */
    protected $shoppingListMapper;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\Customer\CustomerReaderInterface
     */
    protected $customerReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\ShoppingListMapperInterface $shoppingListMapper
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\Customer\CustomerReaderInterface $customerReader
     */
    public function __construct(
        ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade,
        ShoppingListMapperInterface $shoppingListMapper,
        CustomerReaderInterface $customerReader
    ) {
        $this->shoppingListFacade = $shoppingListFacade;
        $this->shoppingListMapper = $shoppingListMapper;
        $this->customerReader = $customerReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    public function getCustomerShoppingListCollection(
        CustomerTransfer $customerTransfer
    ): ShoppingListCollectionTransfer {
        $customerTransfer->requireCompanyUserTransfer();

        $customerResponseTransfer = $this->customerReader->findCustomerByCustomerReferenceAndCompanyUserUuid(
            $customerTransfer->getCustomerReference(),
            $customerTransfer->getCompanyUserTransfer()->getUuid()
        );

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return (new ShoppingListCollectionTransfer())
                ->setShoppingLists(new ArrayObject());
        }

        return $this->shoppingListFacade->getCustomerShoppingListCollection($customerResponseTransfer->getCustomerTransfer());
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function findShoppingListByUuid(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        $restShoppingListRequestTransfer
            ->requireShoppingList()
            ->requireCustomerReference()
            ->requireCompanyUserUuid();
        $restShoppingListRequestTransfer->getShoppingList()
            ->requireUuid();

        $customerResponseTransfer = $this->customerReader->findCustomerByCustomerReferenceAndCompanyUserUuid(
            $restShoppingListRequestTransfer->getCustomerReference(),
            $restShoppingListRequestTransfer->getCompanyUserUuid()
        );

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListMapper->mapCustomerResponseErrorsToShoppingListResponseErrors(
                $customerResponseTransfer,
                new ShoppingListResponseTransfer()
            );
        }

        $shoppingListTransfer = (new ShoppingListTransfer())
            ->setUuid($restShoppingListRequestTransfer->getShoppingList()->getUuid())
            ->setCustomerReference($restShoppingListRequestTransfer->getCustomerReference())
            ->setIdCompanyUser(
                $customerResponseTransfer->getCustomerTransfer()
                    ->getCompanyUserTransfer()
                    ->getIdCompanyUser()
            );

        $shoppingListResponseTransfer = $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            $shoppingListResponseTransfer->addError(SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_NOT_FOUND);

            return $this->shoppingListMapper->mapShoppingListResponseErrorsToRestCodes(
                $shoppingListResponseTransfer
            );
        }

        return $shoppingListResponseTransfer;
    }
}
