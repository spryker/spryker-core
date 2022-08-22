<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Generator;

class CreateProductUrlGenerator implements CreateProductUrlGeneratorInterface
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::indexAction()
     *
     * @var string
     */
    protected const URL_INDEX_ACTION = '/product-merchant-portal-gui/create-product-abstract';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::createWithSingleConcreteAction()
     *
     * @var string
     */
    protected const URL_WITH_SINGLE_CONCRETE_ACTION = '/product-merchant-portal-gui/create-product-abstract/create-with-single-concrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::createWithMultiConcreteAction()
     *
     * @var string
     */
    protected const URL_WITH_MULTI_CONCRETE_ACTION = '/product-merchant-portal-gui/create-product-abstract/create-with-multi-concrete';

    /**
     * @see \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\ProductAbstractApprovalController::indexAction()
     *
     * @var string
     */
    protected const URL_UPDATE_APPROVAL_STATUS = '/product-merchant-portal-gui/product-abstract-approval';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm::FIELD_NAME
     *
     * @var string
     */
    protected const FIELD_NAME = 'name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm::FIELD_SKU
     *
     * @var string
     */
    protected const FIELD_SKU = 'sku';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Form\CreateProductAbstractForm::FIELD_IS_SINGLE_CONCRETE
     *
     * @var string
     */
    protected const FIELD_IS_SINGLE_CONCRETE = 'isSingleConcrete';

    /**
     * @var string
     */
    protected const FIELD_ID_PRODUCT_ABSTRACT = 'id-product-abstract';

    /**
     * @var string
     */
    protected const FIELD_APPROVAL_STATUS = 'approval-status';

    /**
     * @param array<mixed> $formData
     * @param bool $isSingleConcrete
     *
     * @return string
     */
    public function getCreateUrl(array $formData, bool $isSingleConcrete): string
    {
        $getParams = http_build_query(
            [
                static::FIELD_SKU => $formData[static::FIELD_SKU],
                static::FIELD_NAME => $formData[static::FIELD_NAME],
                static::FIELD_IS_SINGLE_CONCRETE => $isSingleConcrete,
            ],
        );

        return sprintf(
            '%s?%s',
            $isSingleConcrete ? static::URL_WITH_SINGLE_CONCRETE_ACTION : static::URL_WITH_MULTI_CONCRETE_ACTION,
            $getParams,
        );
    }

    /**
     * @param string $sku
     * @param string $name
     * @param bool $isSingleConcrete
     *
     * @return string
     */
    public function getCreateProductAbstractUrl(string $sku, string $name, bool $isSingleConcrete): string
    {
        $getParams = http_build_query(
            [
                static::FIELD_SKU => $sku,
                static::FIELD_NAME => $name,
                static::FIELD_IS_SINGLE_CONCRETE => $isSingleConcrete,
            ],
        );

        return sprintf(
            '%s?%s',
            static::URL_INDEX_ACTION,
            $getParams,
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param string $status
     * @param int $idProductAbstract
     *
     * @return string
     */
    public function getUpdateProductAbstractApprovalStatusUrl(string $status, int $idProductAbstract): string
    {
        $getParams = http_build_query(
            [
                static::FIELD_APPROVAL_STATUS => $status,
                static::FIELD_ID_PRODUCT_ABSTRACT => $idProductAbstract,
            ],
        );

        return sprintf(
            '%s?%s',
            static::URL_UPDATE_APPROVAL_STATUS,
            $getParams,
        );
    }
}
