<?php

namespace SprykerFeature\Zed\Category\Business\Generator;

/**
 * Interface UrlPathGeneratorInterface
 *
 * @package SprykerFeature\Zed\CategoryTree\Business\Generator
 */
interface UrlPathGeneratorInterface
{
    /**
     * @param array $categoryPath
     *
     * @return string
     */
    public function generate(array $categoryPath);
}