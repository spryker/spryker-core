<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductApproval\Business\Validator;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer;
use Spryker\Shared\ProductApproval\ProductApprovalConfig;
use Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface;

class ProductApprovalShoppingListValidator implements ProductApprovalShoppingListValidatorInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PRODUCT_NOT_APPROVED = 'product-approval.message.not-approved';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_SKU = '%sku%';

    /**
     * @var \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductApproval\Dependency\Facade\ProductApprovalToProductFacadeInterface $productFacade
     */
    public function __construct(ProductApprovalToProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListPreAddItemCheckResponseTransfer
     */
    public function validateShoppingListItem(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListPreAddItemCheckResponseTransfer {
        $shoppingListPreAddItemCheckResponseTransfer = (new ShoppingListPreAddItemCheckResponseTransfer())
            ->setIsSuccess(true);

        $idProductAbstract = $this->productFacade->getProductAbstractIdByConcreteSku(
            $shoppingListItemTransfer->getSkuOrFail(),
        );

        $productAbstractTransfer = $this->productFacade->findProductAbstractById($idProductAbstract);

        if (!$productAbstractTransfer || $productAbstractTransfer->getApprovalStatus() !== ProductApprovalConfig::STATUS_APPROVED) {
            return $shoppingListPreAddItemCheckResponseTransfer->setIsSuccess(false)
                ->addMessage(
                    (new MessageTransfer())
                        ->setValue(static::GLOSSARY_KEY_PRODUCT_NOT_APPROVED)
                        ->setParameters([static::GLOSSARY_KEY_PARAM_SKU => $shoppingListItemTransfer->getSku()]),
                );
        }

        return $shoppingListPreAddItemCheckResponseTransfer;
    }
}
