<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Business\Model;

use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch;
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
     * @param \Spryker\Zed\ProductPageSearch\Dependency\Service\ProductPageSearchToUtilEncodingInterface $utilEncoding
     */
    public function __construct(ProductPageSearchToUtilEncodingInterface $utilEncoding)
    {
        $this->utilEncoding = $utilEncoding;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array $data
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch|null $productPageSearchEntity
     *
     * @return void
     */
    public function save(ProductPageSearchTransfer $productPageSearchTransfer, array $data, ?SpyProductAbstractPageSearch $productPageSearchEntity = null)
    {
        if ($productPageSearchEntity === null) {
            $productPageSearchEntity = new SpyProductAbstractPageSearch();
        }

        $this->saveEntity($productPageSearchEntity, $productPageSearchTransfer, $data);
    }

    /**
     * @param \Orm\Zed\ProductPageSearch\Persistence\SpyProductAbstractPageSearch $productPageSearchEntity
     * @param \Generated\Shared\Transfer\ProductPageSearchTransfer $productPageSearchTransfer
     * @param array $data
     *
     * @return void
     */
    protected function saveEntity(SpyProductAbstractPageSearch $productPageSearchEntity, ProductPageSearchTransfer $productPageSearchTransfer, array $data)
    {
        $productPageSearchEntity->setFkProductAbstract($productPageSearchTransfer->getIdProductAbstract());
        $productPageSearchEntity->setStructuredData($this->utilEncoding->encodeJson($productPageSearchTransfer->toArray()));
        $productPageSearchEntity->setData($data);
        $productPageSearchEntity->setStore($productPageSearchTransfer->getStore());
        $productPageSearchEntity->setLocale($productPageSearchTransfer->getLocale());
        $productPageSearchEntity->save();
    }
}
