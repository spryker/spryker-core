<?php

namespace Spryker\Zed\Product\Communication\Controller;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Application\Communication\Controller\AbstractController;
use Spryker\Zed\Product\Business\ProductFacade;
use Spryker\Zed\Product\Communication\ProductCommunicationFactory;
use Spryker\Zed\Product\Persistence\ProductQueryContainer;
use Orm\Zed\Product\Persistence\SpyAbstractProduct;
use Orm\Zed\Product\Persistence\SpyProduct;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method ProductFacade getFacade()
 * @method ProductQueryContainer getQueryContainer()
 * @method ProductCommunicationFactory getFactory()
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
        $table = $this->getFactory()->createProductTable();

        return [
            'products' => $table->render(),
        ];
    }

    /**
     * @return JsonResponse
     */
    public function tableAction()
    {
        $table = $this->getFactory()->createProductTable();

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
            ->findOne();

        $concreteProductCollection = $this->getQueryContainer()
            ->queryConcreteProductByAbstractProduct($abstractProduct)
            ->find();

        $concreteProducts = $this->createConcreteProductsCollection($concreteProductCollection);

        $currentLocale = $this->getCurrentLocale();

        $attributesCollection = $this->getQueryContainer()
            ->queryAbstractProductAttributeCollection($abstractProduct->getIdAbstractProduct(), $currentLocale->getIdLocale())
            ->findOne();

        $attributes = [
            'name' => $attributesCollection->getName(),
            'attributes' => $this->mergeAttributes(
                json_decode($attributesCollection->getAttributes(), true),
                json_decode($abstractProduct->getAttributes(), true)
            ),
        ];

        $categories = $this->getProductCategories($abstractProduct, $currentLocale->getIdLocale());

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
     * @param ObjectCollection|SpyProduct[] $concreteProductsCollection
     *
     * @return array
     */
    protected function createConcreteProductsCollection(ObjectCollection $concreteProductsCollection)
    {
        $concreteProducts = [];
        foreach ($concreteProductsCollection as $concreteProduct) {
            $productOptions = $this->getFactory()
                ->createProductOptionsFacade()
                ->getProductOptionsByIdProduct(
                    $concreteProduct->getIdProduct(),
                    $this->getCurrentLocale()->getIdLocale()
                );

            $concreteProducts[] = [
                'sku' => $concreteProduct->getSku(),
                'idProduct' => $concreteProduct->getIdProduct(),
                'isActive' => $concreteProduct->getIsActive(),
                'priceList' => $this->getProductPriceList($concreteProduct),
                'productOptions' => $productOptions,
            ];
        }

        return $concreteProducts;
    }

    /**
     * @param SpyAbstractProduct $abstractProduct
     * @param int $idLocale
     *
     * @return array
     */
    protected function getProductCategories(SpyAbstractProduct $abstractProduct, $idLocale)
    {
        $productCategoryEntityList = $this->getFactory()
            ->createProductCategoryQueryContainer()
            ->queryLocalizedProductCategoryMappingByIdProduct($abstractProduct->getIdAbstractProduct())
            ->find();

        $categories = [];
        foreach ($productCategoryEntityList as $productCategoryEntity) {
            $categories[] = [
                self::COL_ID_PRODUCT_CATEGORY => $productCategoryEntity->getIdProductCategory(),
                self::COL_CATEGORY_NAME => $productCategoryEntity->getSpyCategory()
                    ->getLocalisedAttributes($idLocale)
                    ->getFirst()
                    ->getName(),
            ];
        }

        return $categories;
    }

    /**
     * @throws \ErrorException
     *
     * @return LocaleTransfer
     */
    protected function getCurrentLocale()
    {
        return $this->getFactory()
            ->createLocaleFacade()
            ->getCurrentLocale();
    }

}
