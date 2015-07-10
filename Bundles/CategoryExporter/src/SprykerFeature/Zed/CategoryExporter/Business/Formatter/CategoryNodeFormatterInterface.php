<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

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
