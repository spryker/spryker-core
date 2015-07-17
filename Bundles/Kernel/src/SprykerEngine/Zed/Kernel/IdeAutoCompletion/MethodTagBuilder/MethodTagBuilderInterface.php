<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

interface MethodTagBuilderInterface
{

    /**
     * @param string $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = []);

}
