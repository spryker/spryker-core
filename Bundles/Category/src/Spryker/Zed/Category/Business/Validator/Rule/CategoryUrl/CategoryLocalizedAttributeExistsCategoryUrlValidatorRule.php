<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryLocalizedAttributeExistsCategoryUrlValidatorRule implements CategoryUrlValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_ATTRIBUTE_ENTITY_NOT_FOUND = 'category.validation.category_attribute_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_CATEGORY_ID = '%category_id%';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_LOCALE = '%locale%';

    /**
     * @var \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface
     */
    protected CategoryRepositoryInterface $categoryRepository;

    /**
     * @var \Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\Category\Persistence\CategoryRepositoryInterface $categoryRepository
     * @param \Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, ErrorAdderInterface $errorAdder)
    {
        $this->categoryRepository = $categoryRepository;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $categoryTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $categoryIds = $this->extractCategoryIds($categoryTransfers);
        $categoryLocalizedAttributesTransfersGroupedByIdCategory = $this->categoryRepository
            ->getCategoryAttributesByCategoryIdsGroupByIdCategory($categoryIds);

        foreach ($categoryTransfers as $entityIdentifier => $categoryTransfer) {
            $idCategory = $categoryTransfer->getIdCategoryOrFail();
            $categoryLocalizedAttributesTransfers = $categoryLocalizedAttributesTransfersGroupedByIdCategory[$idCategory] ?? [];

            $errorCollectionTransfer = $this->validateCategoryLocalizedAttributes(
                $errorCollectionTransfer,
                $categoryTransfer,
                $categoryLocalizedAttributesTransfers,
                $entityIdentifier,
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ErrorCollectionTransfer $errorCollectionTransfer
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     * @param list<\Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer> $categoryLocalizedAttributesTransfers
     * @param string|int $entityIdentifier
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    protected function validateCategoryLocalizedAttributes(
        ErrorCollectionTransfer $errorCollectionTransfer,
        CategoryTransfer $categoryTransfer,
        array $categoryLocalizedAttributesTransfers,
        string|int $entityIdentifier
    ): ErrorCollectionTransfer {
        $categoryLocalizedAttributesIndexedByLocaleName = $this->getCategoryLocalizedAttributesTransfersIndexedByLocaleName(
            $categoryLocalizedAttributesTransfers,
        );

        foreach ($categoryTransfer->getLocalizedAttributes() as $categoryLocalizedAttributesTransfer) {
            $localeName = $categoryLocalizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail();
            if (isset($categoryLocalizedAttributesIndexedByLocaleName[$localeName])) {
                continue;
            }

            $errorCollectionTransfer = $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_CATEGORY_ATTRIBUTE_ENTITY_NOT_FOUND,
                [
                    static::GLOSSARY_KEY_PARAM_CATEGORY_ID => $categoryTransfer->getIdCategoryOrFail(),
                    static::GLOSSARY_KEY_PARAM_LOCALE => $localeName,
                ],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return list<int>
     */
    protected function extractCategoryIds(ArrayObject $categoryTransfers): array
    {
        $categoryIds = [];
        foreach ($categoryTransfers as $categoryTransfer) {
            $categoryIds[] = $categoryTransfer->getIdCategoryOrFail();
        }

        return $categoryIds;
    }

    /**
     * @param list<\Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer> $categoryLocalizedAttributesTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer>
     */
    protected function getCategoryLocalizedAttributesTransfersIndexedByLocaleName(array $categoryLocalizedAttributesTransfers): array
    {
        $indexedCategoryLocalizedAttributesTransfers = [];
        foreach ($categoryLocalizedAttributesTransfers as $categoryLocalizedAttributesTransfer) {
            $localeName = $categoryLocalizedAttributesTransfer->getLocaleOrFail()->getLocaleNameOrFail();
            $indexedCategoryLocalizedAttributesTransfers[$localeName] = $categoryLocalizedAttributesTransfer;
        }

        return $indexedCategoryLocalizedAttributesTransfers;
    }
}
