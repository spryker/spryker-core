<?php

namespace SprykerFeature\Zed\Product\Communication\Controller;

use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Zed\Application\Communication\Controller\AbstractController;
use SprykerFeature\Zed\Product\Business\ProductFacade;
use SprykerFeature\Zed\Product\Communication\ProductDependencyContainer;
use SprykerFeature\Zed\Product\Persistence\ProductQueryContainer;
use SprykerFeature\Zed\Product\Persistence\Propel\SpyAbstractProduct;
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
    const COL_ID_PRODUCT_CATEGORY = 'id_product_category';
    const COL_CATEGORY_NAME = 'category_name';

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

        $currentLocale = $this->getCurrentLocale();

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

        $categories = $this->getProductCategories($abstractProduct);

        return $this->viewResponse([
            'abstractProduct' => $abstractProduct,
            'concreteProducts' => $concreteProducts,
            'attributes' => $attributes,
            'categories' => $categories,
        ]);
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

        // @todo this is here for proof of concept, will be changed
        $whiteListPrices = [1,5,10,20,50,100];

        $priceList = [];
        foreach ($productPricesCollection as $priceDefinition) {
            if (!in_array($priceDefinition->getPriceType()->getName(), $whiteListPrices)) {
                continue;
            }
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

            $productOptions = $this->getDependencyContainer()
                ->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_OPTIONS)
                ->getProductOption($product->getIdProduct(), $this->getCurrentLocale()->getLocaleName())
            ;

            $concreteProducts[] = [
                'sku' => $product->getSku(),
                'format' => $product->getFormat(),
                'weight' => $product->getWeight(),
                'type' => $product->getType(),
                'idProduct' => $product->getIdProduct(),
                'isActive' => $product->getIsActive(),
                'priceList' => $this->getProductPriceList($product),
                'productOptions' => $productOptions,
            ];
        }

        return $concreteProducts;
    }

    /**
     * @param $abstractProduct
     *
     * @return array
     */
    protected function getProductCategories(SpyAbstractProduct $abstractProduct)
    {
        $categoriesCollection = $this->getDependencyContainer()
            ->getProvidedDependency(ProductDependencyProvider::FACADE_PRODUCT_CATEGORIES)
            ->getCategoriesByAbstractProduct($abstractProduct)
            ->find()
        ;

        $categories = [];
        foreach ($categoriesCollection as $category) {
            $categories[] = [
                self::COL_ID_PRODUCT_CATEGORY => $category->getIdProductCategory(),
                self::COL_CATEGORY_NAME => $category->getVirtualColumn(self::COL_CATEGORY_NAME)
            ];
        }

        return $categories;
    }

    /**
     * @throws \ErrorException
     * @return mixed
     */
    protected function getCurrentLocale()
    {
        $currentLocale = $this->getDependencyContainer()->getProvidedDependency(ProductDependencyProvider::FACADE_LOCALE)->getCurrentLocale();

        return $currentLocale;
    }
}
