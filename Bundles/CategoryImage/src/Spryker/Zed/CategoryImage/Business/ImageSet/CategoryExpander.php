<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CategoryImage\Business\ImageSet;

use ArrayObject;
use Generated\Shared\Transfer\CategoryTransfer;

class CategoryExpander implements CategoryExpanderInterface
{
    /**
     * @var \Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetReaderInterface
     */
    protected $imageSetReader;

    /**
     * @param \Spryker\Zed\CategoryImage\Business\ImageSet\ImageSetReaderInterface $imageSetReader
     */
    public function __construct(ImageSetReaderInterface $imageSetReader)
    {
        $this->imageSetReader = $imageSetReader;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryTransfer $categoryTransfer
     *
     * @return \Generated\Shared\Transfer\CategoryTransfer
     */
    public function expandCategoryWithImageSets(CategoryTransfer $categoryTransfer): CategoryTransfer
    {
        $categoryImageSets = $this->imageSetReader->getCategoryImageSetsByIdCategory(
            $categoryTransfer->requireIdCategory()->getIdCategory()
        );

        if ($categoryImageSets) {
            $categoryTransfer->setImageSets(
                new ArrayObject($categoryImageSets)
            );
        }

        return $categoryTransfer;
    }
}
