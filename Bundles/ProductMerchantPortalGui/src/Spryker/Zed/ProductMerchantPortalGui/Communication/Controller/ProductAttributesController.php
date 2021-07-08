<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Spryker\Zed\ProductMerchantPortalGui\Communication\Response\ResponseBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductAttributesController extends AbstractController
{
    protected const RESPONSE_DELETE_MESSAGE_SUCCESS = 'Success! The Attribute is deleted.';
    protected const RESPONSE_MESSAGE_SUCCESS = 'Product attributes updated successfully.';
    protected const ERROR_MESSAGE_ATTRIBUTE_NAME_EMPTY = 'The attribute name must be not empty';
    protected const ERROR_MESSAGE_PRODUCT_CANNOT_BE_FOUND = 'Abstract Product cannot be found';
    protected const ERROR_MESSAGE_ALL_ATTRIBUTES_EMPTY = 'Please fill in at least one value';

    protected const PARAM_ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    protected const PARAM_ATTRIBUTE_NAME = 'attribute_name';

    /**
     * @var \Spryker\Zed\ProductMerchantPortalGui\Communication\Response\ResponseFactory
     */
    protected $responseFactory;

    public function __construct()
    {
        $this->responseFactory = $this->getFactory()->createResponseFactory();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attributeDataAction(Request $request): Response
    {
        $inputType = GuiTableConfigurationBuilderInterface::COLUMN_TYPE_AUTOCOMPLETE;
        $typeOptions = ['options' => [], 'type' => 'text'];

        $attributeName = $request->get(static::PARAM_ATTRIBUTE_NAME);

        if (!$attributeName) {
            return new JsonResponse(['type' => $inputType, 'typeOptions' => $typeOptions]);
        }

        $productManagementAttribute = $this->findProductManagementAttribute($attributeName);

        if (!$productManagementAttribute) {
            return new JsonResponse(['type' => $inputType, 'typeOptions' => $typeOptions]);
        }

        $typeOptions = ['options' => $this->getOptions($productManagementAttribute)];

        $inputType = $productManagementAttribute->getAllowInput() ?
            GuiTableConfigurationBuilderInterface::COLUMN_TYPE_AUTOCOMPLETE :
            GuiTableConfigurationBuilderInterface::COLUMN_TYPE_SELECT;

        return new JsonResponse(['type' => $inputType, 'typeOptions' => $typeOptions]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveAction(Request $request): JsonResponse
    {
        $attributeName = $request->get(static::PARAM_ATTRIBUTE_NAME);

        if (empty($attributeName)) {
            return $this->responseFactory->createErrorJsonResponse(static::ERROR_MESSAGE_ATTRIBUTE_NAME_EMPTY);
        }

        $attributes = $this->getAttributes($request);

        if ($this->isAllAttributesEmpty($attributes, $attributeName)) {
            return $this->responseFactory->createErrorJsonResponse(static::ERROR_MESSAGE_ALL_ATTRIBUTES_EMPTY);
        }

        $productAbstractTransfer = $this->findProductAbstract($request);

        if (!$productAbstractTransfer) {
            return $this->responseFactory->createErrorJsonResponse(static::ERROR_MESSAGE_PRODUCT_CANNOT_BE_FOUND);
        }

        $this->updateAttributes($attributes, $productAbstractTransfer, $attributeName);

        $this->getFactory()->getProductFacade()->saveProductAbstract($productAbstractTransfer);

        return $this->responseFactory->createSuccessJsonResponse(ResponseBuilder::POST_ACTION_REFRESH_TABLE, static::RESPONSE_MESSAGE_SUCCESS);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteAction(Request $request): JsonResponse
    {
        $attributeName = $request->get(static::PARAM_ATTRIBUTE_NAME);

        if (!$attributeName) {
            return $this->responseFactory->createErrorJsonResponse(static::ERROR_MESSAGE_ATTRIBUTE_NAME_EMPTY);
        }

        $productAbstractTransfer = $this->findProductAbstract($request);

        if (!$productAbstractTransfer) {
            return $this->responseFactory->createErrorJsonResponse(static::ERROR_MESSAGE_PRODUCT_CANNOT_BE_FOUND);
        }

        $this->deleteAttribute($productAbstractTransfer, (string)$attributeName);

        $this->getFactory()
            ->getProductFacade()
            ->saveProductAbstract($productAbstractTransfer);

        return $this->responseFactory->createSuccessJsonResponse(ResponseBuilder::POST_ACTION_REFRESH_TABLE, static::RESPONSE_DELETE_MESSAGE_SUCCESS);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        $productAbstractTransfer = $this->findProductAbstract($request);

        if (!$productAbstractTransfer) {
            return $this->responseFactory->createErrorJsonResponse(static::ERROR_MESSAGE_PRODUCT_CANNOT_BE_FOUND);
        }

        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductAbstractAttributesTableDataProvider(
                $productAbstractTransfer->getIdProductAbstractOrFail()
            ),
            $this->getFactory()->createProductAbstractAttributeGuiTableConfigurationProvider()->getConfiguration(
                $productAbstractTransfer->getIdProductAbstractOrFail(),
                []
            )
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $attributeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function deleteAttribute(ProductAbstractTransfer $productAbstractTransfer, string $attributeName): ProductAbstractTransfer
    {
        $attributes = $productAbstractTransfer->getAttributes();
        unset($attributes[$attributeName]);
        $productAbstractTransfer->setAttributes($attributes);

        foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
            $attributes = $localizedAttribute->getAttributes();
            unset($attributes[$attributeName]);
            $localizedAttribute->setAttributes($attributes);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    protected function findProductAbstract(Request $request): ?ProductAbstractTransfer
    {
        $idProductAbstract = $this->castId($request->get(static::PARAM_ID_PRODUCT_ABSTRACT));
        $idMerchant = $this->getFactory()->getMerchantUserFacade()->getCurrentMerchantUser()->getIdMerchantOrFail();

        return $this->getFactory()
            ->createProductAbstractFormDataProvider()
            ->findProductAbstract($idProductAbstract, $idMerchant);
    }

    /**
     * @param array $attributes
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param string $attributeName
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    protected function updateAttributes(
        array $attributes,
        ProductAbstractTransfer $productAbstractTransfer,
        string $attributeName
    ): ProductAbstractTransfer {
        foreach ($attributes as $attributeType => $attributeValue) {
            foreach ($productAbstractTransfer->getLocalizedAttributes() as $localizedAttribute) {
                if ($attributeType === $localizedAttribute->getLocaleOrFail()->getLocaleNameOrFail()) {
                    $localeAttributes = $localizedAttribute->getAttributes();

                    unset($localeAttributes[$attributeName]);

                    if (!empty($attributeValue)) {
                        $localeAttributes[$attributeName] = $attributeValue;
                    }

                    $localizedAttribute->setAttributes($localeAttributes);

                    break 2;
                }
            }

            $attributes = $productAbstractTransfer->getAttributes();
            unset($attributes[$attributeName]);

            if (!empty($attributeValue)) {
                $attributes[$attributeName] = $attributeValue;
            }

            $productAbstractTransfer->setAttributes($attributes);
        }

        return $productAbstractTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttribute
     *
     * @return array
     */
    protected function getOptions(ProductManagementAttributeTransfer $productManagementAttribute): array
    {
        $values = $productManagementAttribute->getValues();

        $options = [];
        foreach ($values as $value) {
            if ($value->getValue() !== null) {
                $options[] = [
                    'value' => $value->getValue(),
                    'title' => ucfirst($value->getValue()),
                ];
            }
        }

        return $options;
    }

    /**
     * @param string $attributeName
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    protected function findProductManagementAttribute(string $attributeName): ?ProductManagementAttributeTransfer
    {
        $result = null;

        $productManagementAttributes = $this->getFactory()
            ->getProductAttributeFacade()
            ->getProductAttributeCollection();

        foreach ($productManagementAttributes as $productManagementAttribute) {
            if ($attributeName === $productManagementAttribute->getKey()) {
                $result = $productManagementAttribute;

                break;
            }
        }

        return $result;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return string[]
     */
    private function getAttributes(Request $request): array
    {
        $content = $this->getFactory()
                   ->getUtilEncodingService()
                   ->decodeJson((string)$request->getContent(), true);

        return $content['data'] ?? [];
    }

    /**
     * @param string[] $attributes
     * @param string $attributeName
     *
     * @return bool
     */
    private function isAllAttributesEmpty(array $attributes, string $attributeName): bool
    {
        foreach ($attributes as $attributeType => $attributeValue) {
            if ($attributeType === $attributeName) {
                continue;
            }

            if (!empty($attributeValue)) {
                return false;
            }
        }

        return true;
    }
}
