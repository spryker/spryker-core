<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Processor;

use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\CategoryExporter\Business\Formatter\CategoryNodeFormatterInterface;

class NavigationProcessor implements NavigationProcessorInterface
{
    /**
     * @var KeyBuilderInterface
     */
    protected $navigationKeyBuilder;

    /**
     * @var CategoryNodeFormatterInterface
     */
    protected $nodeFormatter;

    /**
     * @param KeyBuilderInterface $navigationKeyBuilder
     * @param CategoryNodeFormatterInterface $nodeFormatter
     */
    public function __construct(
        KeyBuilderInterface $navigationKeyBuilder,
        CategoryNodeFormatterInterface $nodeFormatter
    ) {
        $this->navigationKeyBuilder = $navigationKeyBuilder;
        $this->nodeFormatter = $nodeFormatter;
    }

    /**
     * @param array $categoryNodes
     * @param string $locale
     * @return array
     */
    public function process(array $categoryNodes, $locale)
    {
        $navigationKey = $this->navigationKeyBuilder->generateKey('', $locale);
        $formattedCategoryNodes = [];
        foreach ($categoryNodes as $categoryNode) {
            $formattedCategoryNodes[] = $this->nodeFormatter->formatCategoryNode($categoryNode);
        }

        return [$navigationKey => $formattedCategoryNodes];
    }
}
