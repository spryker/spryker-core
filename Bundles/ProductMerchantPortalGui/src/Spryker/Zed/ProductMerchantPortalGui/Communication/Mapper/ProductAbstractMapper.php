<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithMultiConcreteForm;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface;
use Symfony\Component\Form\FormInterface;

class AbstractProductMapper
{
    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface
     */
    protected $merchantUserFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade
     */
    public function __construct(ProductMerchantPortalGuiToMerchantUserFacadeInterface $merchantUserFacade)
    {
        $this->merchantUserFacade = $merchantUserFacade;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithMultiConcreteForm
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    public function executeFormSubmission(FormInterface $createProductAbstractWithMultiConcreteForm): ProductAbstractTransfer
    {
        $formData = $createProductAbstractWithMultiConcreteForm->getData();
        $merchantUserTransfer = $this->merchantUserFacade->getCurrentMerchantUser();
        $localeTransfers = $this->localeFacade->getLocaleCollection();

        $productAbstractTransfer = (new ProductAbstractTransfer())
            ->setSku($formData[CreateProductAbstractWithMultiConcreteForm::FIELD_SKU])
            ->setName($formData[CreateProductAbstractWithMultiConcreteForm::FIELD_NAME])
            ->setIdMerchant($merchantUserTransfer->getIdMerchantOrFail());
    }
}
