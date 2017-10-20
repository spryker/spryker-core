<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSet\Business\Model\Data\Url;

use Generated\Shared\Transfer\UrlTransfer;
use Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface;
use Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface;

class ProductSetUrlDeleter implements ProductSetUrlDeleterInterface
{
    /**
     * @var \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface
     */
    protected $productSetQueryContainer;

    /**
     * @var \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface
     */
    protected $urlFacade;

    /**
     * @param \Spryker\Zed\ProductSet\Persistence\ProductSetQueryContainerInterface $productSetQueryContainer
     * @param \Spryker\Zed\ProductSet\Dependency\Facade\ProductSetToUrlInterface $urlFacade
     */
    public function __construct(ProductSetQueryContainerInterface $productSetQueryContainer, ProductSetToUrlInterface $urlFacade)
    {
        $this->productSetQueryContainer = $productSetQueryContainer;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @param int $idProductSet
     *
     * @return void
     */
    public function deleteUrl($idProductSet)
    {
        $productSetUrlCollection = $this->productSetQueryContainer
            ->queryUrlByIdProductSet($idProductSet)
            ->find();

        foreach ($productSetUrlCollection as $urlEntity) {
            $urlTransfer = new UrlTransfer();
            $urlTransfer->setIdUrl($urlEntity->getIdUrl());

            $this->urlFacade->deleteUrl($urlTransfer);
        }
    }
}
