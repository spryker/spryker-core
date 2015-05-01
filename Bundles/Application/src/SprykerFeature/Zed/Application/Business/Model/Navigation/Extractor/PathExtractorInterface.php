<?php
namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Extractor;

interface PathExtractorInterface
{

    /**
     * @param array $menu
     * @return array
     */
    public function extractPathFromMenu(array $menu);
}
