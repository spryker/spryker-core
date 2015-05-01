<?php

namespace SprykerFeature\Zed\Category\Business\Generator;

/**
 * Class UrlGenerator
 *
 * @package SprykerFeature\Zed\CategoryTree\Business\Generator
 */
class UrlPathGenerator implements UrlPathGeneratorInterface
{
    /**
     * @param array $categoryPath
     *
     * @return string
     */
    public function generate(array $categoryPath)
    {
        $formattedPath = [];

        foreach ($categoryPath as $category) {
            if (isset($category['name'])) {
                $categoryName = $category['name'];
                $formattedPath[] = strtolower(str_replace(' ', '-', trim($categoryName)));
            }
        }

        return '/' . implode('/', $formattedPath);
    }
}
