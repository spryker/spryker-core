<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPageSearch\Communication\Plugin\Event;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Zed\EventBehavior\Dependency\Plugin\EventResourceRepositoryPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Spryker\Zed\ProductPageSearch\Persistence\ProductPageSearchRepositoryInterface getRepository()()
 * @method \Spryker\Zed\ProductPageSearch\Business\ProductPageSearchFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductConcretePageSearchEventResourceRepositoryPlugin extends AbstractPlugin implements EventResourceRepositoryPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ProductPageSearchConstants::PRODUCT_CONCRETE_RESOURCE_NAME;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param int[] $ids
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]|\Spryker\Shared\Kernel\Transfer\AbstractEntityTransfer[]
     */
    public function getData(array $ids = []): array
    {
        if (!empty($ids)) {
            return $this->getFactory()->getProductFacade()->findConcreteProductsByIds($ids);
        }

        return $this->getFactory()->getProductFacade()->findAllConcreteProducts();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string
     */
    public function getEventName(): string
    {
        return ProductEvents::PRODUCT_CONCRETE_PUBLISH;
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return string|null
     */
    public function getIdColumnName(): ?string
    {
        return SpyProductTableMap::COL_ID_PRODUCT;
    }
}
