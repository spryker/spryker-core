<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer;
use Spryker\Zed\Category\Business\Extractor\ErrorExtractorInterface;

class CategoryNodeFilter implements CategoryNodeFilterInterface
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
     * @param \Generated\Shared\Transfer\CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
     *
     * @return array<\ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>>
     */
    public function filterCategoryNodesByValidity(
        CategoryNodeCollectionResponseTransfer $categoryNodeCollectionResponseTransfer
    ): array {
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers(
            $categoryNodeCollectionResponseTransfer->getErrors()->getArrayCopy(),
        );

        $validCategoryNodeTransfers = new ArrayObject();
        $notValidCategoryNodeTransfers = new ArrayObject();

        foreach ($categoryNodeCollectionResponseTransfer->getCategoryNodes() as $entityIdentifier => $categoryNodeTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $notValidCategoryNodeTransfers->offsetSet($entityIdentifier, $categoryNodeTransfer);

                continue;
            }

            $validCategoryNodeTransfers->offsetSet($entityIdentifier, $categoryNodeTransfer);
        }

        return [$validCategoryNodeTransfers, $notValidCategoryNodeTransfers];
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $validCategoryNodeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $notValidCategoryNodeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>
     */
    public function mergeCategoryNodes(
        ArrayObject $validCategoryNodeTransfers,
        ArrayObject $notValidCategoryNodeTransfers
    ): ArrayObject {
        foreach ($notValidCategoryNodeTransfers as $entityIdentifier => $notValidCategoryNodeTransfer) {
            $validCategoryNodeTransfers->offsetSet($entityIdentifier, $notValidCategoryNodeTransfer);
        }

        return $validCategoryNodeTransfers;
    }
}
