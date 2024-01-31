<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer;
use Spryker\Zed\Category\Business\Extractor\ErrorExtractorInterface;

class CategoryUrlFilter implements CategoryUrlFilterInterface
{
    /**
     * @var \Spryker\Zed\Category\Business\Extractor\ErrorExtractorInterface
     */
    protected ErrorExtractorInterface $errorExtractor;

    /**
     * @param \Spryker\Zed\Category\Business\Extractor\ErrorExtractorInterface $errorExtractor
     */
    public function __construct(ErrorExtractorInterface $errorExtractor)
    {
        $this->errorExtractor = $errorExtractor;
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryUrlCollectionResponseTransfer $categoryUrlCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer>>
     */
    public function filterCategoriesByValidity(CategoryUrlCollectionResponseTransfer $categoryUrlCollectionResponseTransfer): array
    {
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers(
            $categoryUrlCollectionResponseTransfer->getErrors()->getArrayCopy(),
        );

        $validCategoryTransfers = new ArrayObject();
        $notValidCategoryTransfers = new ArrayObject();

        foreach ($categoryUrlCollectionResponseTransfer->getCategories() as $entityIdentifier => $categoryTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $notValidCategoryTransfers->offsetSet($entityIdentifier, $categoryTransfer);

                continue;
            }

            $validCategoryTransfers->offsetSet($entityIdentifier, $categoryTransfer);
        }

        return [$validCategoryTransfers, $notValidCategoryTransfers];
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $validCategoryTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $notValidCategoryTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer>
     */
    public function mergeCategories(ArrayObject $validCategoryTransfers, ArrayObject $notValidCategoryTransfers): ArrayObject
    {
        foreach ($notValidCategoryTransfers as $entityIdentifier => $notValidCategoryTransfer) {
            $validCategoryTransfers->offsetSet($entityIdentifier, $notValidCategoryTransfer);
        }

        return $validCategoryTransfers;
    }
}
