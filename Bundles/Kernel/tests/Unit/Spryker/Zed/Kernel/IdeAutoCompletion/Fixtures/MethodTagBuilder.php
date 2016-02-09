<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\IdeAutoCompletion\Fixtures;

use Spryker\Zed\Kernel\IdeAutoCompletion\MethodTagBuilder\MethodTagBuilderInterface;

class MethodTagBuilder implements MethodTagBuilderInterface
{

    /**
     * @param string $bundle
     * @param array $methodTags
     *
     * @return array
     */
    public function buildMethodTags($bundle, array $methodTags = [])
    {
        return ['\\Foo\\Bar\\Baz\\Bat' => '\\Foo\\Bar\\Baz\\Bat'];
    }

}
