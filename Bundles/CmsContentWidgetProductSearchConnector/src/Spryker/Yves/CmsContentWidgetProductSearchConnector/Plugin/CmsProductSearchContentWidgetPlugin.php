<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\CmsContentWidget\CmsContentWidgetConfig;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetProductSearchConnector\CmsContentWidgetProductSearchConnectorFactory getFactory()
 */
class CmsProductSearchContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
{
    /**
     * @var \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface
     */
    protected $widgetConfiguration;

    /**
     * @param \Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface $widgetConfiguration
     */
    public function __construct(CmsContentWidgetConfigurationProviderInterface $widgetConfiguration)
    {
        $this->widgetConfiguration = $widgetConfiguration;
    }

    /**
     * @return callable
     */
    public function getContentWidgetFunction()
    {
        return [$this, 'contentWidgetFunction'];
    }

    /**
     * @param \Twig_Environment $twig
     * @param array $context Data related to twig function
     * @param string $searchString $productAbstractSkuList
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(
        Twig_Environment $twig,
        array $context,
        $searchString,
        $templateIdentifier = null
    ) {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $searchString)
        );
    }

    /**
     * @param null|string $templateIdentifier
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier = null)
    {
        if (!$templateIdentifier) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param array $context
     * @param string $productSearchString
     *
     * @return array
     */
    protected function getContent(array $context, $productSearchString)
    {
        $cmsContent = $this->getCmsContent($context);
        $skuMap = $this->getProductAbstractSkuMap($cmsContent);

        $productAbstractSkuMap = $this->findProductAbstractByIdProductAbstract($productSearchString);
        $skuMap = array_merge($skuMap, $productAbstractSkuMap);
        $productAbstractSkuList = array_keys($productAbstractSkuMap);

        if (is_array($productAbstractSkuList)) {
            $products = $this->collectProductAbstractList($productAbstractSkuList, $skuMap);

            $numberOfCollectedProducts = count($products);
            if ($numberOfCollectedProducts > 1) {
                return ['products' => $products];
            }
            if ($numberOfCollectedProducts === 1) {
                return ['product' => array_shift($products)];
            }
            return [];
        }

        return [];
    }

    /**
     * @param array $context
     *
     * @return array
     */
    protected function getCmsContent(array $context)
    {
        return $context['cmsContent'];
    }

    /**
     * @param array $cmsContent
     *
     * @return array
     */
    protected function getProductAbstractSkuMap(array $cmsContent)
    {
        return $cmsContent[CmsContentWidgetConfig::CMS_CONTENT_WIDGET_PARAMETER_MAP][$this->widgetConfiguration->getFunctionName()];
    }

    /**
     * @param string $productSearchString
     *
     * @return string[] [$productSKU => $productId]
     */
    protected function searchProductAbstractSkuMap($productSearchString)
    {
        $response = $this->getFactory()
            ->getSearchClient()
            ->searchKeys($productSearchString)
            ->getResponse()
            ->getData()['hits']['hits'];

        $skuMap = [];
        foreach ($response as $item) {
            if ($item['_source']['type'] !== 'product_abstract') {
                continue;
            }

            $productId = $item['_source']['search-result-data']['id_product_abstract'];
            $productSku = $item['_source']['search-result-data']['abstract_sku'];
            $skuMap[$productSku] = $productId;
        }

        return $skuMap;
    }

    /**
     * @param array $concreteProductSkuList
     * @param array $skuToProductAbstractIdMap
     *
     * @return array
     */
    protected function collectProductAbstractList(array $concreteProductSkuList, array $skuToProductAbstractIdMap)
    {
        $products = [];
        foreach ($concreteProductSkuList as $sku) {
            if (!isset($skuToProductAbstractIdMap[$sku])) {
                continue;
            }

            $productData = $this->findProductAbstractByIdProductAbstract($skuToProductAbstractIdMap[$sku]);
            if (!$productData) {
                continue;
            }

            $productStorageTransfer = $this->mapProductStorageTransfer($productData);

            if (!$productStorageTransfer->getAvailable()) {
                continue;
            }

            $products[] = $productStorageTransfer;
        }

        return $products;
    }

    /**
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function mapProductStorageTransfer(array $productData)
    {
       //implement, this method is overwritten and provided in demoshop
        return (new StorageProductTransfer())->fromArray($productData, true);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return array|null
     */
    protected function findProductAbstractByIdProductAbstract($idProductAbstract)
    {
        $productData = $this->getFactory()
            ->getProductClient()
            ->getProductAbstractFromStorageByIdForCurrentLocale($idProductAbstract);

        if (!$productData) {
            return null;
        }

        return $productData;
    }
}
