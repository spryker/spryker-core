<?php

namespace SprykerFeature\Zed\Product\Communication\Controller;

use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Price\Persistence\Propel\SpyPriceProduct;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Communication\ProductDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\ProductDependencyProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductFacade getFacade()
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductDependencyContainer getDependencyContainer()
 */
class IndexController extends AbstractController
{
    const ID_ABSTRACT_PRODUCT = 'id-abstract-product';

    public function indexAction()
    {

    }

    public function viewAction(Request $request)
    {
        $idAbstractProduct = $request->query->getInt(self::ID_ABSTRACT_PRODUCT);

        $abstractProduct = $this->getQueryContainer()->querySkuFromAbstractProductById($idAbstractProduct)->findOne();
        $concreteProducts = $this->getQueryContainer()->queryConcreteProductByAbstractProduct($abstractProduct)->find();

        $currentLocale = $this->getDependencyContainer()->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE)->getCurrentLocale();

        $attributesCollection = $this->getQueryContainer()->queryAbstractProductAttributeCollection($abstractProduct->getIdAbstractProduct(), $currentLocale->getIdLocale())->findOne();

        $attributes = [
            'name' => $attributesCollection->getName(),
            'attributes' => json_decode($attributesCollection->getAttributes(), true),
        ];

        return $this->viewResponse([
            'abstractProduct' => $abstractProduct,
            'concreteProducts' => $concreteProducts,
            'attributes' => $attributes,
        ]);
    }
}
