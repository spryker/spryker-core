<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use ArrayObject;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Shared\GuiTable\Configuration\Builder\GuiTableConfigurationBuilderInterface;
use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductAttributesController extends AbstractController
{
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_DELETE_SUCCESS = 'Success! The Attribute is deleted.';
    /**
     * @var string
     */
    protected const RESPONSE_NOTIFICATION_MESSAGE_UPDATE_SUCCESS = 'Product attributes updated successfully.';

    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EMPTY_ATTRIBUTE_NAME = 'The attribute name must be not empty';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_EMPTY_ATTRIBUTES = 'Please fill in at least one value';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_ABSTRACT_PRODUCT_CANNOT_BE_FOUND = 'Abstract Product cannot be found';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_CONCRETE_PRODUCT_CANNOT_BE_FOUND = 'Concrete Product cannot be found';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_SUPER_ATTRIBUTE_WAS_NOT_DELETED = 'Super attribute was not deleted';

    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_ABSTRACT = 'idProductAbstract';
    /**
     * @var string
     */
    protected const PARAM_ID_PRODUCT_CONCRETE = 'idProductConcrete';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_NAME
     * @var string
     */
    protected const PARAM_ATTRIBUTE_NAME = 'attribute_name';

    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\GuiTable\ConfigurationProvider\ProductAttributeGuiTableConfigurationProvider::COL_KEY_ATTRIBUTE_DEFAULT
     * @var string
     */
    protected const PARAM_ATTRIBUTE_DEFAULT = 'attribute_default';

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
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_EMPTY_ATTRIBUTE_NAME);
        }

        $productAbstractTransfer = $this->findProductAbstract($request);

        if (!$productAbstractTransfer) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_ABSTRACT_PRODUCT_CANNOT_BE_FOUND);
        }

        $attributes = $this->getAttributes($request);

        $productAttributes = $this->updateProductAttributes($attributes, $productAbstractTransfer->getAttributes(), $attributeName);
        $localizedAttributes = $this->updateLocalizedAttributes($attributes, $productAbstractTransfer->getLocalizedAttributes(), $attributeName);

        if ($this->isAllAttributesEmpty($productAttributes, $localizedAttributes, $attributeName)) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_EMPTY_ATTRIBUTES);
        }

        $productAbstractTransfer->setAttributes($productAttributes)->setLocalizedAttributes($localizedAttributes);
        $this->getFactory()->getProductFacade()->saveProductAbstract($productAbstractTransfer);

        return $this->createSuccessJsonResponse(static::RESPONSE_NOTIFICATION_MESSAGE_UPDATE_SUCCESS);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function saveProductConcreteAttributeAction(Request $request): JsonResponse
    {
        $attributeName = $request->get(static::PARAM_ATTRIBUTE_NAME);

        if (!$attributeName) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_EMPTY_ATTRIBUTE_NAME);
        }

        $idProductConcrete = $this->castId(
            $request->get(static::PARAM_ID_PRODUCT_CONCRETE)
        );

        $productConcreteTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductConcreteById($idProductConcrete);

        if (!$productConcreteTransfer) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_ABSTRACT_PRODUCT_CANNOT_BE_FOUND);
        }

        $attributes = $this->getAttributes($request);

        $productAttributes = $this->updateProductAttributes($attributes, $productConcreteTransfer->getAttributes(), $attributeName);
        $localizedAttributes = $this->updateLocalizedAttributes($attributes, $productConcreteTransfer->getLocalizedAttributes(), $attributeName);

        if ($this->isAllAttributesEmpty($productAttributes, $localizedAttributes, $attributeName)) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_EMPTY_ATTRIBUTES);
        }

        $productConcreteTransfer->setAttributes($productAttributes)->setLocalizedAttributes($localizedAttributes);
        $this->getFactory()->getProductFacade()->saveProductConcrete($productConcreteTransfer);

        return $this->createSuccessJsonResponse(static::RESPONSE_NOTIFICATION_MESSAGE_UPDATE_SUCCESS);
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
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_EMPTY_ATTRIBUTE_NAME);
        }

        $productAbstractTransfer = $this->findProductAbstract($request);

        if (!$productAbstractTransfer) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_ABSTRACT_PRODUCT_CANNOT_BE_FOUND);
        }

        $productAttributes = $productAbstractTransfer->getAttributes();
        $localizedAttributes = $this->deleteLocalizedAttribute($productAbstractTransfer->getLocalizedAttributes(), $attributeName);

        unset($productAttributes[$attributeName]);

        $productAbstractTransfer->setAttributes($productAttributes)->setLocalizedAttributes($localizedAttributes);
        $this->getFactory()->getProductFacade()->saveProductAbstract($productAbstractTransfer);

        return $this->createSuccessJsonResponse(static::RESPONSE_NOTIFICATION_MESSAGE_DELETE_SUCCESS);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function deleteConcreteProductAttributeAction(Request $request): JsonResponse
    {
        $attributeName = $request->get(static::PARAM_ATTRIBUTE_NAME);

        if (!$attributeName) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_EMPTY_ATTRIBUTE_NAME);
        }

        $idProductConcrete = $this->castId(
            $request->get(static::PARAM_ID_PRODUCT_CONCRETE)
        );

        $productConcreteTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductConcreteById($idProductConcrete);

        if (!$productConcreteTransfer) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_CONCRETE_PRODUCT_CANNOT_BE_FOUND);
        }

        $localeTransfer = $this->getFactory()->getLocaleFacade()->getCurrentLocale();
        $superAttributeNames = $this->getFactory()
            ->createLocalizedAttributesExtractor()
            ->extractSuperAttributes(
                $productConcreteTransfer->getAttributes(),
                $productConcreteTransfer->getLocalizedAttributes(),
                $localeTransfer
            );

        if (isset($superAttributeNames[$attributeName])) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_SUPER_ATTRIBUTE_WAS_NOT_DELETED);
        }

        $productAttributes = $productConcreteTransfer->getAttributes();
        $localizedAttributes = $this->deleteLocalizedAttribute($productConcreteTransfer->getLocalizedAttributes(), $attributeName);

        unset($productAttributes[$attributeName]);

        $productConcreteTransfer->setAttributes($productAttributes)->setLocalizedAttributes($localizedAttributes);
        $this->getFactory()->getProductFacade()->saveProductConcrete($productConcreteTransfer);

        return $this->createSuccessJsonResponse(static::RESPONSE_NOTIFICATION_MESSAGE_DELETE_SUCCESS);
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
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_ABSTRACT_PRODUCT_CANNOT_BE_FOUND);
        }

        $idProductAbstract = $productAbstractTransfer->getIdProductAbstractOrFail();
        $guiTableDataProvider = $this->getFactory()
            ->createProductAbstractAttributesTableDataProvider($idProductAbstract);
        $guiTableConfigurationTransfer = $this->getFactory()
            ->createProductAbstractAttributeGuiTableConfigurationProvider()
            ->getConfiguration($idProductAbstract, []);

        return $this->getFactory()
            ->getGuiTableHttpDataRequestExecutor()
            ->execute($request, $guiTableDataProvider, $guiTableConfigurationTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function concreteTableDataAction(Request $request): Response
    {
        $idProductConcrete = $this->castId(
            $request->get(static::PARAM_ID_PRODUCT_CONCRETE)
        );

        $productConcreteTransfer = $this->getFactory()
            ->getProductFacade()
            ->findProductConcreteById($idProductConcrete);

        if (!$productConcreteTransfer) {
            return $this->createErrorJsonResponse(static::ERROR_MESSAGE_CONCRETE_PRODUCT_CANNOT_BE_FOUND);
        }

        $idProductConcrete = $productConcreteTransfer->getIdProductConcreteOrFail();
        $guiTableDataProvider = $this->getFactory()
            ->createProductConcreteAttributesTableDataProvider($idProductConcrete);
        $guiTableConfigurationTransfer = $this->getFactory()
            ->createProductConcreteAttributeGuiTableConfigurationProvider()
            ->getConfiguration($idProductConcrete, []);

        return $this->getFactory()
            ->getGuiTableHttpDataRequestExecutor()
            ->execute($request, $guiTableDataProvider, $guiTableConfigurationTransfer);
    }

    /**
     * @phpstan-param ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @phpstan-return ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributesTransfers
     * @param string $attributeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[]
     */
    protected function deleteLocalizedAttribute(ArrayObject $localizedAttributesTransfers, string $attributeName): ArrayObject
    {
        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $attributes = $localizedAttributesTransfer->getAttributes();

            unset($attributes[$attributeName]);

            $localizedAttributesTransfer->setAttributes($attributes);
        }

        return $localizedAttributesTransfers;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     */
    protected function findProductAbstract(Request $request): ?ProductAbstractTransfer
    {
        $idProductAbstract = $this->castId($request->get(static::PARAM_ID_PRODUCT_ABSTRACT));
        $idMerchant = $this->getFactory()
            ->getMerchantUserFacade()
            ->getCurrentMerchantUser()
            ->getIdMerchantOrFail();

        return $this->getFactory()
            ->createProductAbstractFormDataProvider()
            ->findProductAbstract($idProductAbstract, $idMerchant);
    }

    /**
     * @param string[] $newAttributes
     * @param string[] $productAttributes
     * @param string $attributeName
     *
     * @return array
     */
    protected function updateProductAttributes(
        array $newAttributes,
        array $productAttributes,
        string $attributeName
    ): array {
        foreach ($newAttributes as $attributeKey => $attributeValue) {
            if ($attributeKey === static::PARAM_ATTRIBUTE_DEFAULT) {
                $productAttributes = $this->updateAttribute(
                    $productAttributes,
                    $attributeName,
                    $attributeValue
                );
            }
        }

        return $productAttributes;
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @phpstan-return \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer>
     *
     * @param string[] $newAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributesTransfers
     * @param string $attributeName
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[]
     */
    protected function updateLocalizedAttributes(array $newAttributes, ArrayObject $localizedAttributesTransfers, string $attributeName): ArrayObject
    {
        foreach ($newAttributes as $attributeType => $attributeValue) {
            $localizedAttributesTransfer = $this->getFactory()
                ->createProductAttributeDataProvider()
                ->findLocalizedAttributeByLocaleName($localizedAttributesTransfers, $attributeType);

            if (!$localizedAttributesTransfer) {
                return $localizedAttributesTransfers;
            }

            $localizedAttributes = $this->updateAttribute(
                $localizedAttributesTransfer->getAttributes(),
                $attributeName,
                $attributeValue
            );

            $localizedAttributesTransfer->setAttributes($localizedAttributes);
        }

        return $localizedAttributesTransfers;
    }

    /**
     * @param array $attributes
     * @param string $attributeName
     * @param string $attributeValue
     *
     * @return array
     */
    protected function updateAttribute(array $attributes, string $attributeName, string $attributeValue): array
    {
        unset($attributes[$attributeName]);

        if ($attributeValue) {
            $attributes[$attributeName] = $attributeValue;
        }

        return $attributes;
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
    protected function getAttributes(Request $request): array
    {
        $content = $this->getFactory()
                   ->getUtilEncodingService()
                   ->decodeJson((string)$request->getContent(), true);

        return $content['data'] ?? [];
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @param string[] $attributes
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributesTransfers
     * @param string $attributeName
     *
     * @return bool
     */
    protected function isAllAttributesEmpty(array $attributes, ArrayObject $localizedAttributesTransfers, string $attributeName): bool
    {
        if ($this->existsNotEmptyLocalizedAttribute($localizedAttributesTransfers, $attributeName)) {
            return false;
        }

        return empty($attributes[static::PARAM_ATTRIBUTE_NAME]);
    }

    /**
     * @phpstan-param \ArrayObject<int, \Generated\Shared\Transfer\LocalizedAttributesTransfer> $localizedAttributesTransfers
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\LocalizedAttributesTransfer[] $localizedAttributesTransfers
     * @param string $attributeName
     *
     * @return bool
     */
    protected function existsNotEmptyLocalizedAttribute(ArrayObject $localizedAttributesTransfers, string $attributeName): bool
    {
        foreach ($localizedAttributesTransfers as $localizedAttributesTransfer) {
            $attributes = $localizedAttributesTransfer->getAttributes();
            if (!empty($attributes[$attributeName])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createSuccessJsonResponse(string $message): JsonResponse
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addSuccessNotification($message)
            ->addActionRefreshTable()
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }

    /**
     * @param string $message
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function createErrorJsonResponse(string $message): JsonResponse
    {
        $zedUiFormResponseTransfer = $this->getFactory()
            ->getZedUiFactory()
            ->createZedUiFormResponseBuilder()
            ->addErrorNotification($message)
            ->createResponse();

        return new JsonResponse($zedUiFormResponseTransfer->toArray());
    }
}
