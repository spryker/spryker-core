<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Model;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearchQuery;
use Propel\Runtime\Exception\PropelException;
use Spryker\Shared\ErrorHandler\ErrorLogger;
use Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface;

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */
class ProductPageSearchWriter implements ProductPageSearchWriterInterface
{
    /**
     * @var \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface
     */
    protected $utilEncoding;

    /**
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
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array $data
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
     * @param array $data
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return void
     */
    protected function saveEntity(SpyProductAbstractPageSearch $productPageSearchEntity, ProductPageSearchTransfer $productPageSearchTransfer, array $data)
    {
        try {
            $productPageSearchEntity->setFkProductAbstract($productPageSearchTransfer->getIdProductAbstract());
            $this->applyChangesToEntity($productPageSearchEntity, $productPageSearchTransfer, $data);
            $productPageSearchEntity->save();
        } catch (PropelException $exception) {
            ErrorLogger::getInstance()->log($exception);
            $productPageSearchEntity = SpyProductAbstractPageSearchQuery::create()
                ->filterByFkProductAbstract($productPageSearchTransfer->getIdProductAbstract())
                ->filterByLocale($productPageSearchTransfer->getLocale())
                ->filterByStore($productPageSearchTransfer->getStore())
                ->findOne();
            if ($productPageSearchEntity === null) {
                throw $exception;
            }
            $this->applyChangesToEntity($productPageSearchEntity, $productPageSearchTransfer, $data);
            $productPageSearchEntity->save();
        }
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productPageSearchEntity
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array $data
     *
     * @return void
     */
    protected function applyChangesToEntity(SpyProductAbstractPageSearch $productPageSearchEntity, ProductPageSearchTransfer $productPageSearchTransfer, array $data): void
    {
        $productPageSearchEntity->setStructuredData($this->utilEncoding->encodeJson($productPageSearchTransfer->toArray()));
        $productPageSearchEntity->setData($data);
        $productPageSearchEntity->setStore($productPageSearchTransfer->getStore());
        $productPageSearchEntity->setLocale($productPageSearchTransfer->getLocale());
        $productPageSearchEntity->setIsSendingToQueue($this->isSendingToQueue);
    }
}
