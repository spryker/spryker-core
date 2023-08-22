<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CategoriesBackendApi\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ApiCategoryImageSetTransfer;
use Generated\Shared\Transfer\ApiCategoryImageTransfer;
use Generated\Shared\Transfer\ApiCategoryLocalizedAttributeTransfer;
use Generated\Shared\Transfer\ApiCategoryParentTransfer;
use Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer;
use Generated\Shared\Transfer\CategoryCollectionResponseTransfer;
use Generated\Shared\Transfer\CategoryCollectionTransfer;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryImageTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\GlueErrorTransfer;
use Generated\Shared\Transfer\GlueResourceTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Spryker\Glue\CategoriesBackendApi\CategoriesBackendApiConfig;
use Symfony\Component\HttpFoundation\Response;

class GlueResponseCategoryMapper implements GlueResponseCategoryMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionTransferToGlueResponseTransfer(
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): GlueResponseTransfer {
        // @todo refactor the method according to your needs
        $glueResponseTransfer = new GlueResponseTransfer();
        foreach ($categoryCollectionTransfer->getCategories() as $categoryTransfer) {
            $glueResponseTransfer = $this->addCategoryTransferToGlueResponse($categoryTransfer, $glueResponseTransfer);
        }

        $glueResponseTransfer->setPagination(
            $categoryCollectionTransfer->getPagination(),
        );

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionTransfer $categoryCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionTransferToSingleResourceGlueResponseTransfer(
        CategoryCollectionTransfer $categoryCollectionTransfer
    ): GlueResponseTransfer {
        // @todo refactor the method according to your needs
        $glueResponseTransfer = new GlueResponseTransfer();
        if ($categoryCollectionTransfer->getCategories()->count() > 0) {
            return $this->addCategoryTransferToGlueResponse($categoryCollectionTransfer->getCategories()->offsetGet(0), $glueResponseTransfer);
        }

        return $this->addNotFoundError($glueResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionResponseTransferToGlueResponseTransfer(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): GlueResponseTransfer {
        // @todo refactor the method according to your needs
        $glueResponseTransfer = new GlueResponseTransfer();
        if ($categoryCollectionResponseTransfer->getErrors()->count() !== 0) {
            foreach ($categoryCollectionResponseTransfer->getErrors() as $error) {
                $glueResponseTransfer->addError((new GlueErrorTransfer())->setMessage($error->getMessage()));
            }

            return $glueResponseTransfer;
        }
        if ($categoryCollectionResponseTransfer->getCategories()->count() === 0) {
            return $this->addNotFoundError($glueResponseTransfer);
        }
        foreach ($categoryCollectionResponseTransfer->getCategories() as $categoryTransfer) {
            $glueResponseTransfer = $this->addCategoryTransferToGlueResponse($categoryTransfer, $glueResponseTransfer);
        }

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function mapCategoryCollectionResponseTransferToSingleResourceGlueResponseTransfer(
        CategoryCollectionResponseTransfer $categoryCollectionResponseTransfer
    ): GlueResponseTransfer {
        // @todo refactor the method according to your needs
        $glueResponseTransfer = new GlueResponseTransfer();
        if ($categoryCollectionResponseTransfer->getCategories()->count() > 0) {
            return $this->addCategoryTransferToGlueResponse($categoryCollectionResponseTransfer->getCategories()->offsetGet(0), $glueResponseTransfer);
        }

        return $this->addNotFoundError($glueResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function addNotFoundError(GlueResponseTransfer $glueResponseTransfer): GlueResponseTransfer
    {
        $glueResponseTransfer
            ->setHttpStatus(Response::HTTP_NOT_FOUND)
            ->addError(
                (new GlueErrorTransfer())
                    ->setStatus(Response::HTTP_NOT_FOUND)
                    ->setMessage(Response::$statusTexts[Response::HTTP_NOT_FOUND]),
            );

        return $glueResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param \Generated\Shared\Transfer\GlueResponseTransfer $glueResponseTransfer
     *
     * @return \Generated\Shared\Transfer\GlueResponseTransfer
     */
    public function addCategoryTransferToGlueResponse(
        CategoryTransfer $categoryTransfer,
        GlueResponseTransfer $glueResponseTransfer
    ): GlueResponseTransfer {
        $categoriesBackendApiAttributesTransfer = $this->mapCategoryTransferToCategoriesBackendApiAttributesTransfer($categoryTransfer);

        $apiLocalizedAttributes = $this->mapLocalizedAttributesTransfersToApiCategoryLocalizedAttributes($categoryTransfer->getLocalizedAttributes());
        $categoriesBackendApiAttributesTransfer->setLocalizedAttributes($apiLocalizedAttributes);

        $this->mapCategoryStoreRelationToCategoriesBackendApiAttributesStores($categoryTransfer->getStoreRelationOrFail(), $categoriesBackendApiAttributesTransfer);

        if ($categoryTransfer->getParentCategoryNode()) {
            $categoriesBackendApiAttributesTransfer->setParent(
                $this->mapCategoryTransferParentNodeToApiCategoryParentTransfer($categoryTransfer),
            );
        }

        $apiCategoryImageSetTransfers = $this->mapCategoryImageSetTransfersToApiCategoryImageSetTransfers($categoryTransfer->getImageSets());
        $categoriesBackendApiAttributesTransfer->setImageSets($apiCategoryImageSetTransfers);

        $resourceTransfer = new GlueResourceTransfer();
        $resourceTransfer->setAttributes($categoriesBackendApiAttributesTransfer);
        $resourceTransfer->setId($categoriesBackendApiAttributesTransfer->getCategoryKey());
        $resourceTransfer->setType(CategoriesBackendApiConfig::RESOURCE_TYPE_CATEGORIES);
        $glueResponseTransfer->addResource($resourceTransfer);

        return $glueResponseTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer> $categoryLocalizedAttributesTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ApiCategoryLocalizedAttributeTransfer>
     */
    public function mapLocalizedAttributesTransfersToApiCategoryLocalizedAttributes(ArrayObject $categoryLocalizedAttributesTransfers): ArrayObject
    {
        $apiLocalizedAttributes = new ArrayObject();
        /** @var \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer $categoryLocalizedAttributesTransfer */
        foreach ($categoryLocalizedAttributesTransfers as $categoryLocalizedAttributesTransfer) {
            $apiLocalizedAttribute = new ApiCategoryLocalizedAttributeTransfer();
            $apiLocalizedAttribute->fromArray($categoryLocalizedAttributesTransfer->toArray(), true);
            $apiLocalizedAttribute->setLocale($categoryLocalizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail());

            $apiLocalizedAttributes->append($apiLocalizedAttribute);
        }

        return $apiLocalizedAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\StoreRelationTransfer $storeRelationTransfer
     * @param \Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer
     *
     * @return void
     */
    public function mapCategoryStoreRelationToCategoriesBackendApiAttributesStores(
        StoreRelationTransfer $storeRelationTransfer,
        CategoriesBackendApiAttributesTransfer $categoriesBackendApiAttributesTransfer
    ): void {
        foreach ($storeRelationTransfer->getStores() as $storeRelation) {
            $categoriesBackendApiAttributesTransfer->addStore($storeRelation->getNameOrFail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoriesBackendApiAttributesTransfer
     */
    public function mapCategoryTransferToCategoriesBackendApiAttributesTransfer(CategoryTransfer $categoryTransfer): CategoriesBackendApiAttributesTransfer
    {
        $categoriesBackendApiAttributesTransfer = new CategoriesBackendApiAttributesTransfer();
        $categoriesBackendApiAttributesTransfer->fromArray($categoryTransfer->toArray(), true);

        return $categoriesBackendApiAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCategoryParentTransfer
     */
    public function mapCategoryTransferParentNodeToApiCategoryParentTransfer(CategoryTransfer $categoryTransfer): ApiCategoryParentTransfer
    {
        $apiCategoryParentTransfer = new ApiCategoryParentTransfer();
        $apiCategoryParentTransfer->setSortOrder($categoryTransfer->getCategoryNodeOrFail()->getNodeOrder());

        $parentCategoryNode = $categoryTransfer->getParentCategoryNode();
        if ($parentCategoryNode) {
            $apiCategoryParentTransfer->setCategoryKey($parentCategoryNode->getCategoryOrFail()->getCategoryKeyOrFail());
        }

        return $apiCategoryParentTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $imageSet
     *
     * @return \Generated\Shared\Transfer\ApiCategoryImageSetTransfer
     */
    public function mapCategoryImageSetTransferToApiCategoryImageSetTransfer(CategoryImageSetTransfer $imageSet): ApiCategoryImageSetTransfer
    {
        $apiCategoryImageSetTransfer = new ApiCategoryImageSetTransfer();
        $apiCategoryImageSetTransfer->setLocale($imageSet->getLocaleOrFail()->getLocaleName());
        $apiCategoryImageSetTransfer->setName($imageSet->getName());

        foreach ($imageSet->getCategoryImages() as $categoryImageTransfer) {
            $apiCategoryImageTransfer = $this->mapCategoryImageTransferToApiCategoryImageTransfer($categoryImageTransfer);

            $apiCategoryImageSetTransfer->addImage($apiCategoryImageTransfer);
        }

        return $apiCategoryImageSetTransfer;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\CategoryImageSetTransfer> $categoryImageSetTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ApiCategoryImageSetTransfer>
     */
    public function mapCategoryImageSetTransfersToApiCategoryImageSetTransfers(ArrayObject $categoryImageSetTransfers): ArrayObject
    {
        $apiCategoryImageSetTransfers = new ArrayObject();

        foreach ($categoryImageSetTransfers as $categoryImageSetTransfer) {
            $apiCategoryImageSetTransfers->append(
                $this->mapCategoryImageSetTransferToApiCategoryImageSetTransfer($categoryImageSetTransfer),
            );
        }

        return $apiCategoryImageSetTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryImageTransfer $categoryImageTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCategoryImageTransfer
     */
    public function mapCategoryImageTransferToApiCategoryImageTransfer(CategoryImageTransfer $categoryImageTransfer): ApiCategoryImageTransfer
    {
        $apiCategoryImageTransfer = new ApiCategoryImageTransfer();
        $apiCategoryImageTransfer->fromArray($categoryImageTransfer->toArray(), true);
        $apiCategoryImageTransfer->setSmallUrl($categoryImageTransfer->getExternalUrlSmall());
        $apiCategoryImageTransfer->setLargeUrl($categoryImageTransfer->getExternalUrlLarge());

        return $apiCategoryImageTransfer;
    }
}
