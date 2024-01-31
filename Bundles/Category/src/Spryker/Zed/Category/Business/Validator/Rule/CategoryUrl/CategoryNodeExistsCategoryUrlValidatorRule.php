<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface;
use Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface;

class CategoryNodeExistsCategoryUrlValidatorRule implements CategoryUrlValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND = 'category.validation.category_node_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_CATEGORY_NODE_ID = '%category_node_id%';

    /**
     * @var \Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface
     */
    protected CategoryNodeReaderInterface $categoryNodeReader;

    /**
     * @var \Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface
     */
    protected ErrorAdderInterface $errorAdder;

    /**
     * @param \Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface $categoryNodeReader
     * @param \Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface $errorAdder
     */
    public function __construct(CategoryNodeReaderInterface $categoryNodeReader, ErrorAdderInterface $errorAdder)
    {
        $this->categoryNodeReader = $categoryNodeReader;
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

        $categoryNodeIds = $this->extractCategoryNodeIds($categoryTransfers);
        $nodeCollectionTransfer = $this->categoryNodeReader->getCategoryNodeCollection(
            $this->createCategoryNodeCriteriaTransfer($categoryNodeIds),
        );

        if ($nodeCollectionTransfer->getNodes()->count() === count($categoryNodeIds)) {
            return $errorCollectionTransfer;
        }

        $persistedCategoryNodeIds = $this->extractCategoryNodeIds($nodeCollectionTransfer->getNodes());
        foreach ($categoryTransfers as $entityIdentifier => $categoryTransfer) {
            $idCategoryNode = $categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
            if (in_array($idCategoryNode, $persistedCategoryNodeIds, true)) {
                continue;
            }

            $errorCollectionTransfer = $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_CATEGORY_NODE_ID => $idCategoryNode],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\CategoryTransfer> $categoryTransfers
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIds(ArrayObject $categoryTransfers): array
    {
        $categoryNodeIds = [];
        foreach ($categoryTransfers as $categoryTransfer) {
            $categoryNodeIds[] = $categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }

    /**
     * @param list<int> $categoryNodeIds
     *
     * @return \Generated\Shared\Transfer\CategoryNodeCriteriaTransfer
     */
    protected function createCategoryNodeCriteriaTransfer(array $categoryNodeIds): CategoryNodeCriteriaTransfer
    {
        return (new CategoryNodeCriteriaTransfer())
            ->setCategoryNodeIds($categoryNodeIds)
            ->setWithRelations(false);
    }
}
