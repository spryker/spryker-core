<?php

namespace SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder;

interface MethodTagBuilderInterface
{

    /**
     * @param $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = []);
}
