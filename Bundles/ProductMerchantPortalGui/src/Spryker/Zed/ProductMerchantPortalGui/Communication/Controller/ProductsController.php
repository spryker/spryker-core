<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMerchantPortalGui\Communication\Controller;

use Spryker\Zed\Kernel\Communication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Zed\ProductMerchantPortalGui\Communication\ProductMerchantPortalGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductMerchantPortalGui\Persistence\ProductMerchantPortalGuiRepositoryInterface getRepository()
 */
class ProductsController extends AbstractController
{
    /**
     * @uses \Spryker\Zed\ProductMerchantPortalGui\Communication\Controller\CreateProductAbstractController::indexAction()
     * @var string
     */
    protected const URL_CREATE_PRODUCT_ABSTRACT = '/product-merchant-portal-gui/create-product-abstract';

    /**
     * @var string
     */
    protected const ID_TABLE_PRODUCT_LIST = 'product-list';

    /**
     * @return array<mixed>
     */
    public function indexAction(): array
    {
        return $this->viewResponse([
            'productAbstractTableConfiguration' => $this->getFactory()
                ->createProductAbstractGuiTableConfigurationProvider()
                ->getConfiguration(),
            'urlCreateProductAbstract' => static::URL_CREATE_PRODUCT_ABSTRACT,
            'idTableProductList' => static::ID_TABLE_PRODUCT_LIST,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function tableDataAction(Request $request): Response
    {
        return $this->getFactory()->getGuiTableHttpDataRequestExecutor()->execute(
            $request,
            $this->getFactory()->createProductAbstractTableDataProvider(),
            $this->getFactory()->createProductAbstractGuiTableConfigurationProvider()->getConfiguration(),
        );
    }
}
