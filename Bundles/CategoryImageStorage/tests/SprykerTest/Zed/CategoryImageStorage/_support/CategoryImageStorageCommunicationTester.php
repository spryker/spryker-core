<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CategoryImageStorage;

use ArrayObject;
use Codeception\Actor;
use Generated\Shared\Transfer\CategoryImageSetTransfer;
use Generated\Shared\Transfer\CategoryTransfer;
use Orm\Zed\CategoryImage\Persistence\SpyCategoryImageSetToCategoryImageQuery;
use Orm\Zed\CategoryImageStorage\Persistence\SpyCategoryImageStorageQuery;
use Spryker\Zed\Category\Business\CategoryFacadeInterface;
use Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class CategoryImageStorageCommunicationTester extends Actor
{
    use _generated\CategoryImageStorageCommunicationTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * @param \Generated\Shared\Transfer\CategoryImageSetTransfer $categoryImageSetTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function createCategoryWithImageSet(CategoryImageSetTransfer $categoryImageSetTransfer): CategoryTransfer
    {
        $categoryTransfer = $this->haveCategory();
        $categoryTransfer->setImageSets(new ArrayObject([$categoryImageSetTransfer]));
        $this->getCategoryImageFacade()->createCategoryImageSetsForCategory($categoryTransfer);

        return $categoryTransfer;
    }

    /**
     * @param int $idCategory
     *
     * @return array[]
     */
    public function getCategoryImages(int $idCategory): array
    {
        $categoryStorage = SpyCategoryImageStorageQuery::create()->findOneByFkCategory($idCategory);

        $categoryImages = $categoryStorage->getData()['image_sets'][0]['images'];

        return $categoryImages;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return void
     */
    public function deleteCategoryWithImageSet(CategoryTransfer $categoryTransfer): void
    {
        $this->getCategoryImageFacade()->deleteCategoryImageSetsByIdCategory($categoryTransfer->getIdCategory());
        $this->getCategoryFacade()->delete($categoryTransfer->getIdCategory());
    }

    /**
     * @param array $categoryImages
     *
     * @return void
     */
    public function assertSortingBySortOrder(array $categoryImages): void
    {
        $sortOrderPrevious = 0;
        foreach ($categoryImages as $categoryImage) {
            $sortOrder = SpyCategoryImageSetToCategoryImageQuery::create()
                ->findOneByFkCategoryImage($categoryImage['id_category_image'])
                ->getSortOrder();
            $this->assertTrue(
                $sortOrder >= $sortOrderPrevious
            );
            $sortOrderPrevious = $sortOrder;
        }
    }

    /**
     * @param array $categoryImages
     *
     * @return void
     */
    public function assertSortingByIdCategoryImageSetToCategoryImage(array $categoryImages): void
    {
        $idCategoryImageSetToCategoryImagePrevious = 0;
        foreach ($categoryImages as $categoryImage) {
            $idCategoryImageSetToCategoryImage = SpyCategoryImageSetToCategoryImageQuery::create()
                ->findOneByFkCategoryImage($categoryImage['id_category_image'])
                ->getIdCategoryImageSetToCategoryImage();
            $this->assertTrue(
                $idCategoryImageSetToCategoryImage > $idCategoryImageSetToCategoryImagePrevious
            );
            $idCategoryImageSetToCategoryImagePrevious = $idCategoryImageSetToCategoryImage;
        }
    }

    /**
     * @return \Spryker\Zed\CategoryImage\Business\CategoryImageFacadeInterface
     */
    protected function getCategoryImageFacade(): CategoryImageFacadeInterface
    {
        return $this->getLocator()->categoryImage()->facade();
    }

    /**
     * @return \Spryker\Zed\Category\Business\CategoryFacadeInterface
     */
    protected function getCategoryFacade(): CategoryFacadeInterface
    {
        return $this->getLocator()->category()->facade();
    }
}
