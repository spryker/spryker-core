<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToProductFacadeInterface;
use Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface;

class ProductCustomerPermissionCartValidator implements ProductCustomerPermissionCartValidatorInterface
{
    const MESSAGE_NO_PERMISSION = 'product-cart.validation.error.no-product-permission';

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductCustomerPermission\Persistence\ProductCustomerPermissionQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\ProductCustomerPermission\Dependency\Facade\ProductCustomerPermissionToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductCustomerPermissionQueryContainerInterface $queryContainer,
        ProductCustomerPermissionToProductFacadeInterface $productFacade
    ) {
        $this->queryContainer = $queryContainer;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function checkPermissions(CartChangeTransfer $cartChangeTransfer)
    {
        $cartPreCheckResponseTransfer = new CartPreCheckResponseTransfer();
        $cartPreCheckResponseTransfer->setIsSuccess(true);

        $customer = $cartChangeTransfer->getQuote()->getCustomer();
        if (!$customer) {
            return $cartPreCheckResponseTransfer;
        }

        foreach ($cartChangeTransfer->getItems() as $cartItem) {
            if (!$this->hasPermission($customer->getIdCustomer(), $cartItem->getSku())) {
                $cartPreCheckResponseTransfer->setIsSuccess(false);
                $cartPreCheckResponseTransfer->addMessage($this->createCartErrorMessage());
                break;
            }
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createCartErrorMessage()
    {
        return (new MessageTransfer())
            ->setValue(static::MESSAGE_NO_PERMISSION);
    }

    /**
     * @param int $idCustomer
     * @param string $concreteProductSku
     *
     * @return bool
     */
    protected function hasPermission(int $idCustomer, string $concreteProductSku)
    {
        $idProductAbstract = $this->productFacade
            ->getProductAbstractIdByConcreteSku($concreteProductSku);
        $hasPermission = $this->queryContainer
            ->queryProductCustomerPermissionByCustomerAndProducts($idCustomer, [$idProductAbstract])
            ->count();

        return $hasPermission > 0;
    }
}
