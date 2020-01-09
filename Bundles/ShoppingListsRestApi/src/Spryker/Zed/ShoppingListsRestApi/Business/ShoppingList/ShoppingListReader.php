<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;
use Spryker\Zed\ShoppingListsRestApi\Business\Customer\CustomerReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListReader implements ShoppingListReaderInterface
{
    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface
     */
    protected $shoppingListFacade;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface
     */
    protected $shoppingListMapper;

    /**
     * @var \Spryker\Zed\ShoppingListsRestApi\Business\Customer\CustomerReaderInterface
     */
    protected $customerReader;

    /**
     * @param \Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface $shoppingListFacade
     * @param \Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface $shoppingListMapper
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
     * @return \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer
     */
    public function getCustomerShoppingListCollection(
        CustomerTransfer $customerTransfer
    ): RestShoppingListCollectionResponseTransfer {
        $customerTransfer->requireCompanyUserTransfer();

        $customerResponseTransfer = $this->customerReader->findCustomerByCompanyUserUuidAndCustomerReference(
            $customerTransfer->getCompanyUserTransfer()->getUuid(),
            $customerTransfer->getCustomerReference()
        );

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListMapper->mapCustomerResponseErrorsToRestShoppingListCollectionResponseErrors(
                $customerResponseTransfer,
                new RestShoppingListCollectionResponseTransfer()
            );
        }

        return $this->shoppingListMapper->mapShoppingListCollectionTransferToRestShoppingListCollectionResponseTransfer(
            $this->shoppingListFacade->getCustomerShoppingListCollection(
                $customerResponseTransfer->getCustomerTransfer()
            ),
            new RestShoppingListCollectionResponseTransfer()
        );
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

        $customerResponseTransfer = $this->customerReader->findCustomerByCompanyUserUuidAndCustomerReference(
            $restShoppingListRequestTransfer->getCompanyUserUuid(),
            $restShoppingListRequestTransfer->getCustomerReference()
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
