<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSetConnector\Plugin;

use Generated\Shared\Transfer\ProductSetStorageTransfer;
use Spryker\Shared\CmsContentWidget\CmsContentWidgetConfig;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetProductSetConnector\CmsContentWidgetProductSetConnectorFactory getFactory()
 */
class CmsProductSetContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
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
     * @param array|string $productSetKeys
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(Environment $twig, array $context, $productSetKeys, $templateIdentifier = null)
    {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier),
            $this->getContent($context, $productSetKeys)
        );
    }

    /**
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function resolveTemplatePath($templateIdentifier = null)
    {
        if (!$templateIdentifier) {
            $templateIdentifier = CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;
        }

        return $this->widgetConfiguration->getAvailableTemplates()[$templateIdentifier];
    }

    /**
     * @param array $context
     * @param array|string $productSetKeys
     *
     * @return array
     */
    protected function getContent(array $context, $productSetKeys)
    {
        $cmsContent = $this->getCmsContent($context);

        $productSetKeyMap = $this->getProductSetKeyMap($cmsContent);

        if (is_array($productSetKeys) && count($productSetKeys) > 1) {
            return $this->getProductSetList($context, $productSetKeys, $productSetKeyMap);
        }

        $productSetKey = $this->extractProductSetKey($productSetKeys);

        return $this->getSingleProductSet($context, $productSetKeyMap, $productSetKey);
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
     * @param array $context
     * @param \Generated\Shared\Transfer\ProductSetStorageTransfer $productSetStorageTransfer
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer[]
     */
    protected function mapStorageProducts(array $context, ProductSetStorageTransfer $productSetStorageTransfer)
    {
        return []; //this method is overwritten and provided in demoshop
    }

    /**
     * @param array $cmsPageData
     *
     * @return array
     */
    protected function getProductSetKeyMap(array $cmsPageData)
    {
        return $cmsPageData[CmsContentWidgetConfig::CMS_CONTENT_WIDGET_PARAMETER_MAP][$this->widgetConfiguration->getFunctionName()];
    }

    /**
     * @param array $context
     * @param array $productSetKeys
     * @param array $productSetKeyToIdMap
     *
     * @return array
     */
    protected function getProductSetList(array $context, array $productSetKeys, array $productSetKeyToIdMap)
    {
        $productSets = [];
        foreach ($productSetKeys as $setKey) {
            $productSet = $this->getSingleProductSet($context, $productSetKeyToIdMap, $setKey);
            if (!isset($productSet['productSet'])) {
                continue;
            }

            $productSets[] = $productSet;
        }

        return [
            'productSetList' => $productSets,
        ];
    }

    /**
     * @param int $idProductSet
     *
     * @return \Generated\Shared\Transfer\ProductSetStorageTransfer|null
     */
    protected function getProductSetStorageTransfer($idProductSet)
    {
        return $this->getFactory()->getProductSetClient()->findProductSetByIdProductSet($idProductSet);
    }

    /**
     * @param array $context
     * @param array $productSetKeyMap
     * @param string $setKey
     *
     * @return array
     */
    protected function getSingleProductSet(array $context, array $productSetKeyMap, $setKey)
    {
        if (!isset($productSetKeyMap[$setKey])) {
            return [];
        }

        $productSet = $this->getProductSetStorageTransfer($productSetKeyMap[$setKey]);
        if (!$productSet || !$productSet->getIsActive()) {
            return [];
        }

        return [
            'productSet' => $productSet,
            'storageProducts' => $this->mapStorageProducts($context, $productSet),
        ];
    }

    /**
     * @param array|string $productSetKeys
     *
     * @return string
     */
    protected function extractProductSetKey($productSetKeys)
    {
        if (is_array($productSetKeys)) {
            return array_shift($productSetKeys);
        }

        return $productSetKeys;
    }
}
