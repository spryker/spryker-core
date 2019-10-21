<?php

/**
 * This file is part of the Spryker Suite.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerTest\Glue\Testify\Tester;

use Spryker\Glue\AlternativeProductsRestApi\AlternativeProductsRestApiConfig;
use Spryker\Glue\CartsRestApi\CartsRestApiConfig;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Spryker\Glue\ProductLabelsRestApi\ProductLabelsRestApiConfig;
use Spryker\Glue\ProductsRestApi\ProductsRestApiConfig;
use Spryker\Glue\RelatedProductsRestApi\RelatedProductsRestApiConfig;
use Spryker\Glue\UpSellingProductsRestApi\UpSellingProductsRestApiConfig;
use Spryker\Glue\WishlistsRestApi\WishlistsRestApiConfig;
use Spryker\Shared\Config\Config;
use Spryker\Shared\Testify\TestifyConstants;
use SprykerTest\Shared\Testify\Tester\EndToEndTester;

abstract class ApiEndToEndTester extends EndToEndTester
{
    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function formatUrl(string $url, $params = []): string
    {
        $refinedParams = [];

        foreach ($params as $key => $value) {
            $refinedParams['{' . $key . '}'] = urlencode($value);
        }

        return strtr($url, $refinedParams);
    }

    /**
     * @param string $url
     * @param array $params
     *
     * @return string
     */
    public function formatFullUrl(string $url, $params = []): string
    {
        return rtrim(Config::get(TestifyConstants::GLUE_APPLICATION_DOMAIN) . '/' . $this->formatUrl($url, $params), '/');
    }

    /**
     * @param array $includes
     *
     * @return string
     */
    public function formatQueryInclude(array $includes = []): string
    {
        if (!$includes) {
            return '';
        }

        return '?' . RequestConstantsInterface::QUERY_INCLUDE . '=' . implode(',', $includes);
    }

    /**
     * @param string $productAbstractSku
     * @param array $includes
     *
     * @return string
     */
    public function buildProductAbstractUrl(string $productAbstractSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceAbstractProducts}/{productAbstractSku}' . $this->formatQueryInclude($includes),
            [
                'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                'productAbstractSku' => $productAbstractSku,
            ]
        );
    }

    /**
     * @param string $productConcreteSku
     * @param array $includes
     *
     * @return string
     */
    public function buildProductConcreteUrl(string $productConcreteSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceConcreteProducts}/{productConcreteSku}' . $this->formatQueryInclude($includes),
            [
                'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                'productConcreteSku' => $productConcreteSku,
            ]
        );
    }

    /**
     * @param string $productConcreteSku
     * @param array $includes
     *
     * @return string
     */
    public function buildAbstractAlternativeProductsUrl(string $productConcreteSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceConcreteProducts}/{productConcreteSku}/{resourceAbstractAlternativeProducts}' . $this->formatQueryInclude($includes),
            [
                'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                'resourceAbstractAlternativeProducts' => AlternativeProductsRestApiConfig::RELATIONSHIP_NAME_ABSTRACT_ALTERNATIVE_PRODUCTS,
                'productConcreteSku' => $productConcreteSku,
            ]
        );
    }

    /**
     * @param string $productConcreteSku
     * @param array $includes
     *
     * @return string
     */
    public function buildConcreteAlternativeProductsUrl(string $productConcreteSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceConcreteProducts}/{productConcreteSku}/{resourceConcreteAlternativeProducts}' . $this->formatQueryInclude($includes),
            [
                'resourceConcreteProducts' => ProductsRestApiConfig::RESOURCE_CONCRETE_PRODUCTS,
                'resourceConcreteAlternativeProducts' => AlternativeProductsRestApiConfig::RELATIONSHIP_NAME_CONCRETE_ALTERNATIVE_PRODUCTS,
                'productConcreteSku' => $productConcreteSku,
            ]
        );
    }

    /**
     * @param string $productAbstractSku
     * @param array $includes
     *
     * @return string
     */
    public function buildRelatedProductsUrl(string $productAbstractSku, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceAbstractProducts}/{productAbstractSku}/{resourceRelatedProducts}' . $this->formatQueryInclude($includes),
            [
                'resourceAbstractProducts' => ProductsRestApiConfig::RESOURCE_ABSTRACT_PRODUCTS,
                'resourceRelatedProducts' => RelatedProductsRestApiConfig::CONTROLLER_RELATED_PRODUCTS,
                'productAbstractSku' => $productAbstractSku,
            ]
        );
    }

    /**
     * @param int $idProductLabel
     * @param array $includes
     *
     * @return string
     */
    public function buildProductLabelUrl(int $idProductLabel, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceProductLabels}/{idProductLabel}' . $this->formatQueryInclude($includes),
            [
                'resourceProductLabels' => ProductLabelsRestApiConfig::RESOURCE_PRODUCT_LABELS,
                'idProductLabel' => $idProductLabel,
            ]
        );
    }

    /**
     * @param array $includes
     *
     * @return string
     */
    public function buildCartsUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
            ]
        );
    }

    /**
     * @param string $cartUuid
     * @param array $includes
     *
     * @return string
     */
    public function buildCartUrl(string $cartUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}/{cartUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                'cartUuid' => $cartUuid,
            ]
        );
    }

    /**
     * @param string $cartUuid
     * @param array $includes
     *
     * @return string
     */
    public function buildCartUpSellingProductsUrl(string $cartUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceCarts}/{cartUuid}/{resourceUpSellingProducts}' . $this->formatQueryInclude($includes),
            [
                'resourceCarts' => CartsRestApiConfig::RESOURCE_CARTS,
                'resourceUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                'cartUuid' => $cartUuid,
            ]
        );
    }

    /**
     * @param array $includes
     *
     * @return string
     */
    public function buildGuestCartsUrl(array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceGuestCarts}' . $this->formatQueryInclude($includes),
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
            ]
        );
    }

    /**
     * @param string $guestCartUuid
     * @param array $includes
     *
     * @return string
     */
    public function buildGuestCartUrl(string $guestCartUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceGuestCarts}/{guestCartUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'guestCartUuid' => $guestCartUuid,
            ]
        );
    }

    /**
     * @param string $cartUuid
     * @param array $includes
     *
     * @return string
     */
    public function buildGuestCartUpSellingProductsUrl(string $cartUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceGuestCarts}/{cartUuid}/{resourceUpSellingProducts}' . $this->formatQueryInclude($includes),
            [
                'resourceGuestCarts' => CartsRestApiConfig::RESOURCE_GUEST_CARTS,
                'resourceUpSellingProducts' => UpSellingProductsRestApiConfig::RELATIONSHIP_NAME_UP_SELLING_PRODUCTS,
                'cartUuid' => $cartUuid,
            ]
        );
    }

    /**
     * @param string $wishlistUuid
     * @param array $includes
     *
     * @return string
     */
    public function buildWishlistUrl(string $wishlistUuid, array $includes = []): string
    {
        return $this->formatFullUrl(
            '{resourceWishlists}/{wishlistUuid}' . $this->formatQueryInclude($includes),
            [
                'resourceWishlists' => WishlistsRestApiConfig::RESOURCE_WISHLISTS,
                'wishlistUuid' => $wishlistUuid,
            ]
        );
    }
}
