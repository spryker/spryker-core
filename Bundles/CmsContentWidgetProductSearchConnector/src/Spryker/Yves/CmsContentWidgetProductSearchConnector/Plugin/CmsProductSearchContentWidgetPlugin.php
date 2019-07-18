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
use Twig\Environment;

/**
 * @method \Spryker\Yves\CmsContentWidgetProductSearchConnector\CmsContentWidgetProductSearchConnectorFactory getFactory()
 * @method \Spryker\Yves\CmsContentWidgetProductSearchConnector\CmsContentWidgetProductSearchConnectorConfig getConfig()
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
     * @param \Twig\Environment $twig
     * @param array $context Data related to twig function
     * @param string $searchString String for direct search in elastic
     * @param string|null $templateIdentifier
     *
     * @return string
     */
    public function contentWidgetFunction(
        Environment $twig,
        array $context,
        $searchString,
        $templateIdentifier = null
    ) {
        return $twig->render(
            $this->resolveTemplatePath($templateIdentifier, $context),
            $this->getTwigWidgetContent($searchString)
        );
    }

    /**
     * @param string $templateIdentifier
     * @param array $context
     *
     * @throws \Spryker\Yves\CmsContentWidgetProductSearchConnector\Exception\TemplateIdentifierNotFoundException
     *
     * @return string
     */
    protected function resolveTemplatePath($templateIdentifier, array $context)
    {
        $availableTemplates = $this->widgetConfiguration->getAvailableTemplates();
        $templateIdentifier = $templateIdentifier ?: CmsContentWidgetConfigurationProviderInterface::DEFAULT_TEMPLATE_IDENTIFIER;

        if (!array_key_exists($templateIdentifier, $availableTemplates)) {
            throw new TemplateIdentifierNotFoundException(
                sprintf(
                    'Template identifier %s not found. Context: {%s}',
                    $templateIdentifier,
                    json_encode($context)
                )
            );
        }

        return $availableTemplates[$templateIdentifier];
    }

    /**
     * @param string $productSearchString
     *
     * @return array
     */
    protected function getTwigWidgetContent($productSearchString)
    {
        $idProductAbstracts = $this->searchIdProductAbstracts($productSearchString);

        if (is_array($idProductAbstracts)) {
            $products = $this->collectProductAbstractList($idProductAbstracts);

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
     * @return int[]
     */
    protected function searchIdProductAbstracts($productSearchString)
    {
        $limit = $this->getConfig()
            ->getSearchLimit();

        $elasticResponse = $this->getFactory()
            ->getSearchClient()
            ->searchQueryString($productSearchString, $limit)
            ->getResponse()
            ->getData();
        $dataResponse = $elasticResponse['hits']['hits'];

        $idProductAbstracts = [];
        foreach ($dataResponse as $item) {
            if ($item['_source']['type'] !== 'product_abstract') {
                continue;
            }

            $idProductAbstracts[] = $item['_source']['search-result-data']['id_product_abstract'];
        }

        return $idProductAbstracts;
    }

    /**
     * @param int[] $idProductAbstracts
     *
     * @return array
     */
    protected function collectProductAbstractList(array $idProductAbstracts)
    {
        $products = [];
        foreach ($idProductAbstracts as $idProductAbstract) {
            $productData = $this->findProductAbstractByIdProductAbstract($idProductAbstract);
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
