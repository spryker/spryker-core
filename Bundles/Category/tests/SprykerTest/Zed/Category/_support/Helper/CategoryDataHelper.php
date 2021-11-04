<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Category\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CategoryBuilder;
use Generated\Shared\DataBuilder\CategoryLocalizedAttributesBuilder;
use Generated\Shared\DataBuilder\NodeBuilder;
use Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer;
use Generated\Shared\Transfer\CategoryTemplateTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\NodeTransfer;
use Orm\Zed\Category\Persistence\SpyCategory;
use Orm\Zed\Category\Persistence\SpyCategoryAttribute;
use Orm\Zed\Category\Persistence\SpyCategoryAttributeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryNode;
use Orm\Zed\Category\Persistence\SpyCategoryNodeQuery;
use Orm\Zed\Category\Persistence\SpyCategoryStore;
use Orm\Zed\Category\Persistence\SpyCategoryStoreQuery;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\Category\CategoryConfig;
use SprykerTest\Shared\Testify\Helper\DataCleanupHelperTrait;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CategoryDataHelper extends Module
{
    use DataCleanupHelperTrait;
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveCategory(array $seedData = []): CategoryTransfer
    {
        $seedData = $seedData + [
            'categoryNode' => $this->generateCategoryNodeTransfer(),
            'parentCategoryNode' => $this->generateCategoryNodeTransfer(),
        ];

        $categoryTransfer = $this->generateCategoryTransfer($seedData);

        $this->getCategoryFacade()->create($categoryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryTransfer): void {
            $this->cleanupCategory($categoryTransfer);
        });

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveLocalizedCategory(array $seedData = []): CategoryTransfer
    {
        $parentNode = $this->getCategoryFacade()->getAllNodesByIdCategory(2)[0];

        $seedData += [
            'categoryNode' => $seedData[CategoryTransfer::CATEGORY_NODE] ?? $this->generateCategoryNodeTransfer(),
            'parentCategoryNode' => $seedData[CategoryTransfer::PARENT_CATEGORY_NODE] ?? $parentNode,
        ];

        $categoryTransfer = $this->generateLocalizedCategoryTransfer($seedData);

        $this->getCategoryFacade()->create($categoryTransfer);

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryTransfer): void {
            $this->cleanupCategory($categoryTransfer);
        });

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer|null
     */
    public function haveCategoryTemplate(array $seedData = []): ?CategoryTemplateTransfer
    {
        $categoryTemplateTransfer = $this->findCategoryTemplateByName(CategoryConfig::CATEGORY_TEMPLATE_DEFAULT);

        $categoryTemplateTransfer->fromArray($seedData, true);

        return $categoryTemplateTransfer;
    }

    /**
     * @param int $idCategory
     * @param int $idStore
     *
     * @return void
     */
    public function haveCategoryStoreRelation(int $idCategory, int $idStore): void
    {
        $categoryStoreEntity = SpyCategoryStoreQuery::create()
            ->filterByFkCategory($idCategory)
            ->filterByFkStore($idStore)
            ->findOneOrCreate();

        if ($categoryStoreEntity->isNew()) {
            $categoryStoreEntity->save();
        }

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryStoreEntity): void {
            $this->cleanupCategoryStoreRelation($categoryStoreEntity);
        });
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function haveCategoryWithoutCategoryNode(array $seedData = []): CategoryTransfer
    {
        $categoryTransfer = $this->generateCategoryTransfer($seedData);

        $categoryEntity = new SpyCategory();
        $categoryEntity->fromArray($categoryTransfer->toArray());
        $categoryEntity->save();

        $categoryTransfer = $categoryTransfer->fromArray(
            $categoryEntity->toArray(),
            true,
        );

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryTransfer): void {
            $this->cleanupCategory($categoryTransfer);
        });

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryLocalizedAttributesTransfer
     */
    public function haveCategoryLocalizedAttributeForCategory(
        int $idCategory,
        array $seedData
    ): CategoryLocalizedAttributesTransfer {
        $categoryAttributeEntity = new SpyCategoryAttribute();
        $categoryAttributeEntity->fromArray($seedData);

        /** @var \Generated\Shared\Transfer\LocaleTransfer $localeTransfer */
        $localeTransfer = $seedData[LocalizedAttributesTransfer::LOCALE] ?? null;
        if ($localeTransfer !== null) {
            $categoryAttributeEntity->setFkLocale($localeTransfer->getIdLocale());
        }

        $categoryAttributeEntity->setFkCategory($idCategory);
        $categoryAttributeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryAttributeEntity): void {
            $this->cleanupCategoryAttribute($categoryAttributeEntity);
        });

        return (new CategoryLocalizedAttributesTransfer())
            ->fromArray($categoryAttributeEntity->toArray(), true);
    }

    /**
     * @param int $idCategory
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    public function haveCategoryNodeForCategory(int $idCategory, array $seedData = []): NodeTransfer
    {
        $nodeTransfer = $this->generateCategoryNodeTransfer($seedData);

        $categoryNodeEntity = new SpyCategoryNode();

        $categoryNodeEntity->fromArray($nodeTransfer->toArray());
        $categoryNodeEntity->setFkCategory($idCategory);
        $categoryNodeEntity->save();

        $this->getDataCleanupHelper()->_addCleanup(function () use ($categoryNodeEntity): void {
            $this->cleanupCategoryNode($categoryNodeEntity);
        });

        return $nodeTransfer->fromArray($categoryNodeEntity->toArray(), true);
    }

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\CategoryTemplateTransfer|null
     */
    protected function findCategoryTemplateByName(string $name): ?CategoryTemplateTransfer
    {
        $spyCategoryTemplate = $this->getLocator()
            ->category()
            ->queryContainer()
            ->queryCategoryTemplateByName($name)
            ->findOne();

        if (!$spyCategoryTemplate) {
            return null;
        }

        return (new CategoryTemplateTransfer())->fromArray($spyCategoryTemplate->toArray(), true);
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function getCategoryFacade(): CategoryFacadeInterface
    {
        return $this->getLocator()->category()->facade();
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function generateCategoryTransfer(array $seedData = []): CategoryTransfer
    {
        $categoryTransfer = (new CategoryBuilder($seedData))->build();
        $categoryTransfer->setIdCategory(null);

        if (!isset($seedData[CategoryTransfer::FK_CATEGORY_TEMPLATE])) {
            $categoryTemplateTransfer = $this->haveCategoryTemplate();
            $categoryTransfer->setFkCategoryTemplate($categoryTemplateTransfer->getIdCategoryTemplate());
        }

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    protected function generateLocalizedCategoryTransfer(array $seedData = []): CategoryTransfer
    {
        $categoryTransfer = $this->generateCategoryTransfer($seedData);
        $categoryLocalizedAttributes = (new CategoryLocalizedAttributesBuilder($seedData))->withLocale()->build();
        $locale = $this->getLocator()->locale()->facade()->getCurrentLocale();
        $categoryLocalizedAttributes->setLocale($locale);
        $categoryTransfer->addLocalizedAttributes($categoryLocalizedAttributes);

        return $categoryTransfer;
    }

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\NodeTransfer
     */
    protected function generateCategoryNodeTransfer(array $seedData = []): NodeTransfer
    {
        $categoryNodeTransfer = (new NodeBuilder($seedData))->build();

        return $categoryNodeTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    protected function cleanupCategory(CategoryTransfer $categoryTransfer): void
    {
        $this->getCategoryFacade()->delete($categoryTransfer->getIdCategory());
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryStore $categoryStoreEntity
     *
     * @return void
     */
    protected function cleanupCategoryStoreRelation(SpyCategoryStore $categoryStoreEntity): void
    {
        SpyCategoryStoreQuery::create()
            ->filterByIdCategoryStore($categoryStoreEntity->getIdCategoryStore())
            ->delete();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryAttribute $categoryAttributeEntity
     *
     * @return void
     */
    protected function cleanupCategoryAttribute(SpyCategoryAttribute $categoryAttributeEntity): void
    {
        SpyCategoryAttributeQuery::create()
            ->filterByIdCategoryAttribute($categoryAttributeEntity->getIdCategoryAttribute())
            ->delete();
    }

    /**
     * @param \Orm\Zed\Category\Persistence\SpyCategoryNode $categoryNodeEntity
     *
     * @return void
     */
    protected function cleanupCategoryNode(SpyCategoryNode $categoryNodeEntity): void
    {
        SpyCategoryNodeQuery::create()
            ->filterByIdCategoryNode($categoryNodeEntity->getIdCategoryNode())
            ->delete();
    }
}
