<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductConnector\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\CmsContentWidget\CmsContentWidgetConfig;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetProductConnector\CmsContentWidgetProductConnectorFactory getFactory()
 */
class CmsProductContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
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
     * @param \Twig\Environment $twig
     * @param array $context
     * @param array|string $productAbstractSkuList $productAbstractSkuList
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Environment $twig, array $context, $productAbstractSkuList, $templateIdentifier = null)
    {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $productAbstractSkuList)
        );
    }

    /**
     * @param string|null $templateIdentifier
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
     * @param array|string $productAbstractSkuList
     *
     * @return array
     */
    protected function getContent(array $context, $productAbstractSkuList)
    {
        $cmsContent = $this->getCmsContent($context);

        $skuMap = $this->getProductAbstractSkuMap($cmsContent);
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

        $productAbstractSku = $productAbstractSkuList;
        if (!isset($skuMap[$productAbstractSku])) {
            return [];
        }

        $storageProductTransfer = $this->findProductAbstractByIdProductAbstract($skuMap[$productAbstractSku]);
        if (!$storageProductTransfer) {
            return [];
        }

        return ['product' => $storageProductTransfer];
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
