<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryNode;

use ArrayObject;
use Generated\Shared\Transfer\CategoryNodeCriteriaTransfer;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Category\Business\Reader\CategoryNodeReaderInterface;
use Spryker\Zed\Category\Business\Validator\Rule\TerminationAwareValidatorRuleInterface;
use Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface;

class CategoryNodeExistsCategoryNodeValidationRule implements CategoryNodeValidatorRuleInterface, TerminationAwareValidatorRuleInterface
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
    public function __construct(
        CategoryNodeReaderInterface $categoryNodeReader,
        ErrorAdderInterface $errorAdder
    ) {
        $this->categoryNodeReader = $categoryNodeReader;
        $this->errorAdder = $errorAdder;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return \Generated\Shared\Transfer\ErrorCollectionTransfer
     */
    public function validate(ArrayObject $categoryNodeTransfers): ErrorCollectionTransfer
    {
        $errorCollectionTransfer = new ErrorCollectionTransfer();

        $categoryNodeIds = $this->extractCategoryNodeIdsFromCategoryNodeTransfers($categoryNodeTransfers);
        $categoryNodeCollectionTransfer = $this->categoryNodeReader->getCategoryNodeCollection(
            (new CategoryNodeCriteriaTransfer())->setCategoryNodeIds($categoryNodeIds),
        );

        $notExistingCategoryNodeIds = array_diff(
            $categoryNodeIds,
            $this->extractCategoryNodeIdsFromCategoryNodeTransfers($categoryNodeCollectionTransfer->getNodes()),
        );

        if (!$notExistingCategoryNodeIds) {
            return $errorCollectionTransfer;
        }

        foreach ($notExistingCategoryNodeIds as $entityIdentifier => $idCategoryNode) {
            $this->errorAdder->addError(
                $errorCollectionTransfer,
                $entityIdentifier,
                static::GLOSSARY_KEY_VALIDATION_CATEGORY_NODE_ENTITY_NOT_FOUND,
                [static::GLOSSARY_KEY_PARAM_CATEGORY_NODE_ID => $idCategoryNode],
            );
        }

        return $errorCollectionTransfer;
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $initialErrorTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ErrorTransfer> $postValidationErrorTransfers
     *
     * @return bool
     */
    public function isTerminated(
        ArrayObject $initialErrorTransfers,
        ArrayObject $postValidationErrorTransfers
    ): bool {
        return $postValidationErrorTransfers->count() > $initialErrorTransfers->count();
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\NodeTransfer> $categoryNodeTransfers
     *
     * @return list<int>
     */
    protected function extractCategoryNodeIdsFromCategoryNodeTransfers(ArrayObject $categoryNodeTransfers): array
    {
        $categoryNodeIds = [];
        foreach ($categoryNodeTransfers as $categoryNodeTransfer) {
            $categoryNodeIds[] = $categoryNodeTransfer->getIdCategoryNodeOrFail();
        }

        return $categoryNodeIds;
    }
}
