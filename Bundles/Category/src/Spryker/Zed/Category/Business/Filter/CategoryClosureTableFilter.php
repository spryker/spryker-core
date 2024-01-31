<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer;
use Spryker\Zed\Category\Business\Extractor\ErrorExtractorInterface;

class CategoryClosureTableFilter implements CategoryClosureTableFilterInterface
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
     * @param \Generated\Shared\Transfer\CategoryClosureTableCollectionResponseTransfer $categoryClosureTableCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>>
     */
    public function filterCategoryNodesByValidity(
        CategoryClosureTableCollectionResponseTransfer $categoryClosureTableCollectionResponseTransfer
    ): array {
        $erroredEntityIdentifiers = $this->errorExtractor->extractEntityIdentifiersFromErrorTransfers(
            $categoryClosureTableCollectionResponseTransfer->getErrors()->getArrayCopy(),
        );

        $validNodeTransfers = new ArrayObject();
        $notValidNodeTransfers = new ArrayObject();
        foreach ($categoryClosureTableCollectionResponseTransfer->getCategoryNodes() as $entityIdentifier => $nodeTransfer) {
            if (isset($erroredEntityIdentifiers[$entityIdentifier])) {
                $notValidNodeTransfers->offsetSet($entityIdentifier, $nodeTransfer);

                continue;
            }

            $validNodeTransfers->offsetSet($entityIdentifier, $nodeTransfer);
        }

        return [$validNodeTransfers, $notValidNodeTransfers];
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $validNodeTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $notValidNodeTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer>
     */
    public function mergeCategoryNodes(ArrayObject $validNodeTransfers, ArrayObject $notValidNodeTransfers): ArrayObject
    {
        foreach ($notValidNodeTransfers as $entityIdentifier => $notValidNodeTransfer) {
            $validNodeTransfers->offsetSet($entityIdentifier, $notValidNodeTransfer);
        }

        return $validNodeTransfers;
    }
}
