<?php

namespace SprykerFeature\Zed\Application\Business\Model\Url;

interface UrlBuilderInterface
{
    /**
     * @param $bundle
     * @param string $controller
     * @param string $action
     * @param array $queryParameter
     * @return string
     */
    public function build($bundle, $controller = null, $action = null, array $queryParameter = []);
}