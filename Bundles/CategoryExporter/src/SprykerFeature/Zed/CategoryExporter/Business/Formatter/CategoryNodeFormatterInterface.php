<?php

namespace SprykerFeature\Zed\CategoryExporter\Business\Formatter;

interface CategoryNodeFormatterInterface
{
    /**
     * @param array $categoryNode
     *
     * @return array
     */
    public function formatCategoryNode(array $categoryNode);
}
