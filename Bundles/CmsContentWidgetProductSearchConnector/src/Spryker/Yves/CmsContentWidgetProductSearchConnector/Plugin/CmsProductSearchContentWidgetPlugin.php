<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CmsContentWidgetProductSearchConnector\Plugin;

use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\CmsContentWidget\Dependency\CmsContentWidgetConfigurationProviderInterface;
use Spryker\Yves\CmsContentWidget\Dependency\CmsContentWidgetPluginInterface;
use Spryker\Yves\CmsContentWidgetProductSearchConnector\Exception\TemplateIdentifierNotFoundException;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig_Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetProductSearchConnector\CmsContentWidgetProductSearchConnectorFactory getFactory()
 */
class CmsProductSearchContentWidgetPlugin extends AbstractPlugin implements CmsContentWidgetPluginInterface
{
    const SEARCH_LIMIT = 20;

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
     * @param string $searchString String for direct search in elastic
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
            $this->getContent($searchString)
        );
    }

    /**
     * @param string $templateIdentifier
     *
     * @throws \Spryker\Yves\CmsContentWidgetProductSearchConnector\Exception\TemplateIdentifierNotFoundException
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier)
    {
        $availableTemplates = $this->widgetConfiguration->getAvailableTemplates();
        $templateIdentifier = $templateIdentifier ?: CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;

        if (!array_key_exists($templateIdentifier, $availableTemplates)) {
            throw new TemplateIdentifierNotFoundException();
        }

        return $availableTemplates[$templateIdentifier];
    }

    /**
     * @param string $productSearchString
     *
     * @return array Variables for twig template
     */
    protected function getContent($productSearchString)
    {
        $productAbstractIds = $this->searchProductAbstractIds($productSearchString);

        if (is_array($productAbstractIds)) {
            $products = $this->collectProductAbstractList($productAbstractIds);

            $numberOfCollectedProducts = count($products);
            if ($numberOfCollectedProducts > 1) {
                return ['products' => $products];
            }
            if ($numberOfCollectedProducts === 1) {
                return ['product' => array_shift($products)];
            }
        }

        return [];
    }

    /**
     * @param string $productSearchString
     *
     * @return int[] Product abstract ids
     */
    protected function searchProductAbstractIds($productSearchString)
    {
        $elasticResponse = $this->getFactory()
            ->getSearchClient()
            ->searchQueryString($productSearchString, static::SEARCH_LIMIT)
            ->getResponse()
            ->getData();
        $dataResponce = $elasticResponse['hits']['hits'];

        $productAbstractIds = [];
        foreach ($dataResponce as $item) {
            if ($item['_source']['type'] !== 'product_abstract') {
                continue;
            }

            $productAbstractIds[] = $item['_source']['search-result-data']['id_product_abstract'];
        }

        return $productAbstractIds;
    }

    /**
     * @param int[] $productAbstractIds
     *
     * @return array
     */
    protected function collectProductAbstractList(array $productAbstractIds)
    {
        $products = [];
        foreach ($productAbstractIds as $productAbstractId) {
            $productData = $this->findProductAbstractByIdProductAbstract($productAbstractId);
            if (!$productData) {
                continue;
            }

            $productStorageTransfer = $this->hydrateProductStorageTransfer($productData);

            $products[] = $productStorageTransfer;
        }

        return $products;
    }

    /**
     * This method should hydrate all data in product transfer.
     * Including availability, images e.t.c.
     *
     * @param array $productData
     *
     * @return \Generated\Shared\Transfer\StorageProductTransfer
     */
    protected function hydrateProductStorageTransfer(array $productData)
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
