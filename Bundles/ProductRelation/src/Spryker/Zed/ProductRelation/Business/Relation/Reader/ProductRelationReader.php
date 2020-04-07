<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Business\Relation\Reader;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductRelationResponseTransfer;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface;

class ProductRelationReader implements ProductRelationReaderInterface
{
    protected const ERROR_MESSAGE_NOT_FOUND = 'Product relation #%d not found';

    /**
     * @var \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface
     */
    protected $productRelationRepository;

    /**
     * @param \Spryker\Zed\ProductRelation\Persistence\ProductRelationRepositoryInterface $productRelationRepository
     */
    public function __construct(
        ProductRelationRepositoryInterface $productRelationRepository
    ) {
        $this->productRelationRepository = $productRelationRepository;
    }

    /**
     * @param int $idProductRelation
     *
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    public function findProductRelationById(int $idProductRelation): ProductRelationResponseTransfer
    {
        $productRelationResponseTransfer = $this->createProductRelationResponseTransfer();
        $productRelationTransfer = $this->productRelationRepository
            ->findProductRelationById($idProductRelation);

        if (!$productRelationTransfer) {
            $messageTransfer = $this->createErrorMessageTransfer(sprintf(static::ERROR_MESSAGE_NOT_FOUND, $idProductRelation));

            return $productRelationResponseTransfer->addMessage($messageTransfer);
        }

        return $productRelationResponseTransfer
            ->setIsSuccessful(true)
            ->setProductRelation($productRelationTransfer);
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createErrorMessageTransfer(string $message): MessageTransfer
    {
        return (new MessageTransfer())
            ->setValue($message);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductRelationResponseTransfer
     */
    protected function createProductRelationResponseTransfer(): ProductRelationResponseTransfer
    {
        return (new ProductRelationResponseTransfer())
            ->setIsSuccessful(false);
    }
}
