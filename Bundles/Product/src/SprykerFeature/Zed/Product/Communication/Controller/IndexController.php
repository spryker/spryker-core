<?php

namespace SprykerFeature\Zed\Product\Communication\Controller;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Communication\ProductDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyProduct;
use SprykerFeature\Zed\Product\ProductDependencyProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductFacade getFacade()
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductDependencyContainer getDependencyContainer()
 */
class IndexController extends AbstractController
{
    const ID_ABSTRACT_PRODUCT = 'id-abstract-product';

    /**
     * @return array
     */
    public function indexAction()
    {
        $table = $this->getDependencyContainer()->createProductTable();

        return [
            'products' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getDependencyContainer()->createProductTable();

        return $this->jsonResponse(
            $table->fetchData()
        );
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function viewAction(Request $request)
    {
        $idAbstractProduct = $request->query->getInt(self::ID_ABSTRACT_PRODUCT);

        $abstractProduct = $this->getQueryContainer()
            ->querySkuFromAbstractProductById($idAbstractProduct)
            ->findOne()
        ;

        $concreteProductsCollenction = $this->getQueryContainer()
            ->queryConcreteProductByAbstractProduct($abstractProduct)
            ->find()
        ;

        $concreteProducts = $this->createConcreteProductsCollection($concreteProductsCollenction);

        $currentLocale = $this->getDependencyContainer()
            ->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE)
            ->getCurrentLocale()
        ;

        $attributesCollection = $this->getQueryContainer()
            ->queryAbstractProductAttributeCollection($abstractProduct->getIdAbstractProduct(), $currentLocale->getIdLocale())
            ->findOne()
        ;

        $attributes = [
            'name' => $attributesCollection->getName(),
            'attributes' => $this->mergeAttributes(
                json_decode($attributesCollection->getAttributes(), true),
                json_decode($abstractProduct->getAttributes(), true)
            ),
        ];

        $out = $this->viewResponse([
            'abstractProduct' => $abstractProduct,
            'concreteProducts' => $concreteProducts,
            'attributes' => $attributes,
        ]);

        return $out;
    }

    /**
     * @param array $attributes
     * @param array $prioritizedAttributes
     *
     * @return array
     */
    protected function mergeAttributes(array $attributes, array $prioritizedAttributes)
    {
        $attributes = array_merge($attributes, $prioritizedAttributes);

        return $attributes;
    }

    /**
     * @param SpyProduct $product
     *
     * @return array
     */
    protected function getProductPriceList(SpyProduct $product)
    {
        $productPricesCollection = $product->getPriceProductsJoinPriceType();

        $priceList = [];
        foreach ($productPricesCollection as $priceDefinition) {
            $priceList[] = [
                'name' => $priceDefinition->getPriceType()->getName(),
                'value' => $priceDefinition->getPrice(),
            ];
        }

        return $priceList;
    }

    /**
     * @param ObjectCollection $concreteProductsCollenction
     *
     * @return array
     */
    protected function createConcreteProductsCollection(ObjectCollection $concreteProductsCollenction)
    {
        $concreteProducts = [];
        foreach ($concreteProductsCollenction as $product) {
            $concreteProducts[] = [
                'sku' => $product->getSku(),
                'format' => $product->getFormat(),
                'weight' => $product->getWeight(),
                'type' => $product->getType(),
                'idProduct' => $product->getIdProduct(),
                'isActive' => $product->getIsActive(),
                'priceList' => $this->getProductPriceList($product),
            ];
        }

        return $concreteProducts;
    }
}
