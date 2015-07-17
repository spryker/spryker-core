<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\CategoryExporter\Business\Formatter\CategoryNodeFormatterInterface;

class CategoryNodeProcessor implements CategoryNodeProcessorInterface
{

    /**
     * @var KeyBuilderInterface
     */
    protected $resourceKeyBuilder;

    /**
     * @var CategoryNodeFormatterInterface
     */
    protected $nodeFormatter;

    /**
     * @param KeyBuilderInterface $keyBuilder
     * @param CategoryNodeFormatterInterface $nodeFormatter
     */
    public function __construct(
        KeyBuilderInterface $keyBuilder,
        CategoryNodeFormatterInterface $nodeFormatter
    ) {
        $this->resourceKeyBuilder = $keyBuilder;
        $this->nodeFormatter = $nodeFormatter;
    }

    /**
     * @param array $categoryNodes
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    public function process(array $categoryNodes, LocaleTransfer $locale)
    {
        $exportData = [];
        foreach ($categoryNodes as $index => $categoryNode) {
            $categoryKey = $this->resourceKeyBuilder->generateKey($categoryNode['node_id'], $locale->getLocaleName());
            $exportData[$categoryKey] = $this->nodeFormatter->formatCategoryNode($categoryNode);
            unset($categoryNodes[$index]);
        }

        return $exportData;
    }

}
