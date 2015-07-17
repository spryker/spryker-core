<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Application\Business\Model\Navigation\Formatter;

interface MenuFormatterInterface
{

    /**
     * @param array $pages
     * @param string $pathInfo
     *
     * @return array
     */
    public function formatMenu(array $pages, $pathInfo);

}
