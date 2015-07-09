<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Url;

interface UrlBuilderInterface
{

    /**
     * @param string $bundle
     * @param string $controller
     * @param string $action
     * @param array $queryParameter
     *
     * @return string
     */
    public function build($bundle, $controller = null, $action = null, array $queryParameter = []);

}
