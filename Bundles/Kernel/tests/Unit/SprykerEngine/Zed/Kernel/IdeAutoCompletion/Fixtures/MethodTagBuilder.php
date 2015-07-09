<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerEngine\Zed\Kernel\IdeAutoCompletion\Fixtures;

use SprykerEngine\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\MethodTagBuilderInterface;

class MethodTagBuilder implements MethodTagBuilderInterface
{

    /**
     * @param $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = [])
    {
        return ['\\Foo\\Bar\\Baz\\Bat' => '\\Foo\\Bar\\Baz\\Bat'];
    }

}
