<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Category\Business\Validator\Rule\CategoryUrl;

use ArrayObject;
use Generated\Shared\Transfer\ErrorCollectionTransfer;
use Spryker\Zed\Category\Business\Validator\Util\ErrorAdderInterface;
use Spryker\Zed\Category\Persistence\CategoryRepositoryInterface;

class CategoryClosureTableExistsCategoryUrlValidatorRule implements CategoryUrlValidatorRuleInterface
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_VALIDATION_CATEGORY_CLOSURE_TABLE_ENTITY_NOT_FOUND = 'category.validation.category_closure_table_entity_not_found';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_PARAM_CATEGORY_NODE_ID = '%category_node_id%';

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

        $categoryNodeIds = $this->extractCategoryNodeIds($categoryTransfers);
        $categoryNodeIdsWithCategoryClosureTableEntities = $this->categoryRepository
            ->getCategoryNodeIdsWithZeroDepthCategoryClosureTableEntities($categoryNodeIds);

        foreach ($categoryTransfers as $entityIdentifier => $categoryTransfer) {
            $idCategoryNode = $categoryTransfer->getCategoryNodeOrFail()->getIdCategoryNodeOrFail();
            if (!in_array($idCategoryNode, $categoryNodeIdsWithCategoryClosureTableEntities, true)) {
                $this->errorAdder->addError(
                    $errorCollectionTransfer,
                    $entityIdentifier,
                    static::GLOSSARY_KEY_VALIDATION_CATEGORY_CLOSURE_TABLE_ENTITY_NOT_FOUND,
                    [static::GLOSSARY_KEY_PARAM_CATEGORY_NODE_ID => $idCategoryNode],
                );
            }
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
}
