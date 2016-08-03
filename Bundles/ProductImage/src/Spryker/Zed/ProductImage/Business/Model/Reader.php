<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductImage\Business\Model;

use Spryker\Zed\ProductImage\Persistence\ProductImageQueryContainerInterface;


class Reader implements ReaderInterface
{

    /**
     * @var ProductImageQueryContainerInterface
     */
    protected $productImageContainer;

    /**
     * @param ProductImageQueryContainerInterface $productImageContainer
     */
    public function __construct(ProductImageQueryContainerInterface $productImageContainer)
    {
        $this->productImageContainer = $productImageContainer;
    }

    /**
     * @param $idProductAbstract
     *
     * @return array
     */
    public function getProductImagesByProductAbstractId($idProductAbstract)
    {
        $imageCollection = $this->productImageContainer
            ->queryImageSetByProductAbstractId($idProductAbstract)
            ->find();

        return $imageCollection;

        $data = [];
        foreach ($imageCollection as $image) {
            $imageSet = $image->getSpyProductImageSetToProductImages();

            foreach ($imageSet as $setEntity) {
                $idSet = (int)$setEntity->getSpyProductImageSet()->getIdProductImageSet();
                $fkLocale = (int)$setEntity->getSpyProductImageSet()->getFkLocale();

                $item = $image->toArray();
                $item[ImageForm::FIELD_SET_ID] = $idSet;
                $item[ImageForm::FIELD_SET_NAME] = $setEntity->getSpyProductImageSet()->getName();
                $item[ImageForm::FIELD_SET_FK_LOCALE] = $setEntity->getSpyProductImageSet()->getFkLocale();
                $item[ImageForm::FIELD_SET_FK_PRODUCT] = $setEntity->getSpyProductImageSet()->getFkProduct();
                $item[ImageForm::FIELD_SET_FK_PRODUCT_ABSTRACT] = $setEntity->getSpyProductImageSet()->getFkProductAbstract();
                $item[ImageCollectionForm::FIELD_ORDER] = $setEntity->getSort();

                $data[] = $item;
            }
        }
    }

}
