<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabel\Business\Label;

use Generated\Shared\Transfer\ProductLabelTransfer;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException;
use Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface;

class LabelReader implements LabelReaderInterface
{

    /**
     * @var \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabel\Persistence\ProductLabelQueryContainerInterface $queryContainer
     */
    public function __construct(ProductLabelQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param int $idProductLabel
     *
     * @throws \Spryker\Zed\ProductLabel\Business\Exception\MissingProductLabelException
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    public function read($idProductLabel)
    {
        $productLabelEntity = $this->findEntity($idProductLabel);

        if (!$productLabelEntity) {
            throw new MissingProductLabelException(
                sprintf('Could not find product label for id "%s"', $idProductLabel)
            );
        }

        $productLabelTransfer = $this->createTransferFromEntity($productLabelEntity);

        return $productLabelTransfer;
    }

    /**
     * @param int $idProductLabel
     *
     * @return \Orm\Zed\ProductLabel\Persistence\SpyProductLabel|null
     */
    protected function findEntity($idProductLabel)
    {
        return $this
            ->queryContainer
            ->queryProductLabelById($idProductLabel)
            ->findOne();
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function createTransferFromEntity(SpyProductLabel $productLabelEntity)
    {
        $productLabelTransfer = new ProductLabelTransfer();
        $productLabelTransfer->fromArray($productLabelEntity->toArray(), true);

        return $productLabelTransfer;
    }

}
