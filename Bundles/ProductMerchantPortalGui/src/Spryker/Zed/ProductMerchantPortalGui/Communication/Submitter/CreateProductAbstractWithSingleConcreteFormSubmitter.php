<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Submitter;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductAbstractMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapperInterface;
use Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface;
use Symfony\Component\Form\FormInterface;

class CreateProductAbstractWithSingleConcreteFormSubmitter implements CreateProductAbstractWithSingleConcreteFormSubmitterInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper::FIELD_NAME
     */
    protected const FIELD_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapper::FIELD_SKU
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_NAME
     */
    protected const FIELD_CONCRETE_NAME = 'concreteName';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractWithSingleConcreteForm::FIELD_CONCRETE_SKU
     */
    protected const FIELD_CONCRETE_SKU = 'concreteSku';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductAbstractMapperInterface
     */
    protected $productAbstractMapper;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapperInterface
     */
    protected $productConcreteMapper;

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductAbstractMapperInterface $productAbstractMapper
     * @param \Spryker\Zed\ProductMerchantPortalGui\Communication\Mapper\ProductConcreteMapperInterface $productConcreteMapper
     * @param \Spryker\Zed\ProductMerchantPortalGui\Dependency\Facade\ProductMerchantPortalGuiToProductFacadeInterface $productFacade
     */
    public function __construct(
        ProductAbstractMapperInterface $productAbstractMapper,
        ProductConcreteMapperInterface $productConcreteMapper,
        ProductMerchantPortalGuiToProductFacadeInterface $productFacade
    ) {
        $this->productAbstractMapper = $productAbstractMapper;
        $this->productConcreteMapper = $productConcreteMapper;
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $createProductAbstractWithSingleConcreteForm
     *
     * @return int
     */
    public function executeFormSubmission(FormInterface $createProductAbstractWithSingleConcreteForm): int
    {
        $formData = $createProductAbstractWithSingleConcreteForm->getData();

        $productAbstractTransfer = $this->productAbstractMapper
            ->mapFormDataToProductAbstractTransfer(
                $formData,
                new ProductAbstractTransfer()
            );

        $productConcreteTransfers = $this->productConcreteMapper
            ->mapRequestDataToProductConcreteTransfers([
                [
                    static::FIELD_SKU => $formData[static::FIELD_CONCRETE_SKU],
                    static::FIELD_NAME => $formData[static::FIELD_CONCRETE_NAME],
                ],
            ]);

        return $this->productFacade->addProduct($productAbstractTransfer, $productConcreteTransfers);
    }
}
