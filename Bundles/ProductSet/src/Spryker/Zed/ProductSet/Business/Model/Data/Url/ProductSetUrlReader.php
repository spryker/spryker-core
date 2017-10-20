<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

use Spryker\Zed\ProductSet\Business\Exception\ProductSetUrlNotFoundException;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetUrlReader implements ProductSetUrlReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
    }

    /**
     * @param int $idProductSet
     * @param int $idLocale
     *
     * @throws \Spryker\Zed\ProductSet\Business\Exception\ProductSetUrlNotFoundException
     *
     * @return \Orm\Zed\Url\Persistence\SpyUrl
     */
    public function getProductSetUrlEntity($idProductSet, $idLocale)
    {
        $urlEntity = $this->productSetQueryContainer
            ->queryUrlByIdProductSet($idProductSet, $idLocale)
            ->findOne();

        if (!$urlEntity) {
            throw new ProductSetUrlNotFoundException(sprintf(
                'Product Set URL not found for #%d and locale #%d.',
                $idProductSet,
                $idLocale
            ));
        }

        return $urlEntity;
    }
}
