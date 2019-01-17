<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList;

use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListResponseTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Zed\ShoppingListsRestApi\Business\Customer\CustomerReaderInterface;
use Spryker\Zed\ShoppingListsRestApi\Business\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Zed\ShoppingListsRestApi\Dependency\Facade\ShoppingListsRestApiToShoppingListFacadeInterface;

class ShoppingListCreator implements ShoppingListCreatorInterface
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
     * @param \Generated\Shared\Transfer\RestShoppingListRequestTransfer $restShoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListResponseTransfer
     */
    public function createShoppingList(
        RestShoppingListRequestTransfer $restShoppingListRequestTransfer
    ): ShoppingListResponseTransfer {
        $restShoppingListRequestTransfer
            ->requireShoppingList()
            ->requireCustomerReference()
            ->requireCompanyUserUuid();
        $restShoppingListRequestTransfer->getShoppingList()
            ->requireName();

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
            ->setName($restShoppingListRequestTransfer->getShoppingList()->getName())
            ->setCustomerReference($restShoppingListRequestTransfer->getCustomerReference())
            ->setIdCompanyUser($customerResponseTransfer->getCustomerTransfer()->getCompanyUserTransfer()->getIdCompanyUser());

        $shoppingListResponseTransfer = $this->shoppingListFacade->createShoppingList($shoppingListTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListMapper->mapShoppingListResponseErrorsToRestCodes(
                $shoppingListResponseTransfer
            );
        }

        $shoppingListTransfer->setUuid($shoppingListResponseTransfer->getShoppingList()->getUuid());

        return $this->shoppingListFacade->findShoppingListByUuid($shoppingListTransfer);
    }
}
