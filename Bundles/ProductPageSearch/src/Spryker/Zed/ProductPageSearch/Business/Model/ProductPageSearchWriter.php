<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Model;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;
use Spryker\Zed\Propel\Persistence\BatchProcessor\ActiveRecordBatchProcessorTrait;

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
class ProductPageSearchWriter implements ProductPageSearchWriterInterface
{
    use ActiveRecordBatchProcessorTrait;

    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
     * @deprecated Use {@link \Spryker\Zed\SynchronizationBehavior\SynchronizationBehaviorConfig::isSynchronizationEnabled()} instead.
     *
     * @var bool
     */
    protected $isSendingToQueue = true;

    /**
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     * @param bool $isSendingToQueue
     */
    public function __construct(ProductPageSearchToUtilEncodingInterface $utilEncoding, $isSendingToQueue)
    {
        $this->utilEncoding = $utilEncoding;
        $this->isSendingToQueue = $isSendingToQueue;
    }

    /**
     * @return void
     */
    public function commitRemaining(): void
    {
        $this->commit();
    }

    /**
     * @param array<\Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch> $productAbstractPageSearchEntities
     *
     * @return void
     */
    public function deleteProductAbstractPageSearchEntities(array $productAbstractPageSearchEntities)
    {
        foreach ($productAbstractPageSearchEntities as $productAbstractPageSearchEntity) {
            $this->remove($productAbstractPageSearchEntity);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array<string, mixed> $data
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productPageSearchEntity
     *
     * @return void
     */
    public function save(ProductPageSearchTransfer $productPageSearchTransfer, array $data, SpyProductAbstractPageSearch $productPageSearchEntity)
    {
        $this->saveEntity($productPageSearchEntity, $productPageSearchTransfer, $data);
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productPageSearchEntity
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function saveEntity(SpyProductAbstractPageSearch $productPageSearchEntity, ProductPageSearchTransfer $productPageSearchTransfer, array $data)
    {
        $productPageSearchEntity->setFkProductAbstract($productPageSearchTransfer->getIdProductAbstract());
        $this->applyChangesToEntity($productPageSearchEntity, $productPageSearchTransfer, $data);
        $this->persist($productPageSearchEntity);
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productPageSearchEntity
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function applyChangesToEntity(
        SpyProductAbstractPageSearch $productPageSearchEntity,
        ProductPageSearchTransfer $productPageSearchTransfer,
        array $data
    ): void {
        $productPageSearchEntity->setStructuredData($this->utilEncoding->encodeJson($productPageSearchTransfer->toArray()));
        $productPageSearchEntity->setData($data);
        $productPageSearchEntity->setStore($productPageSearchTransfer->getStore());
        $productPageSearchEntity->setLocale($productPageSearchTransfer->getLocale());
        $productPageSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
    }
}
