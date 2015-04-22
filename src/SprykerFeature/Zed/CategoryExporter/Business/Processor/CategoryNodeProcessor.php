<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\CategoryExporter\Business\Formatter\CategoryNodeFormatterInterface;
use SprykerFeature\Shared\Category\CategoryResourceSettings;

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
     * @param string $locale
     *
     * @return array
     */
    public function process(array $categoryNodes, $locale)
    {
        $exportData = [];
        foreach ($categoryNodes as $index => $categoryNode) {
            $categoryKey = $this->resourceKeyBuilder->generateKey(
                [
                    'resourceType' => CategoryResourceSettings::ITEM_TYPE,
                    'value' => $categoryNode['node_id']
                ],
                $locale
            );
            $exportData[$categoryKey] = $this->nodeFormatter->formatCategoryNode($categoryNode);
            unset($categoryNodes[$index]);
        }
        return $exportData;
    }
}
