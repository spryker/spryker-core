<?php

declare(strict_types = 1);

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Rector\Symfony\Security\New_;

use PhpParser\Node;
use PhpParser\Node\Arg;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\New_;
use PhpParser\Node\Name;
use Rector\Core\Rector\AbstractRector;
use Rector\Core\RectorDefinition\CodeSample;
use Rector\Core\RectorDefinition\RectorDefinition;

final class BCryptToNativePasswordEncoderArgumentRector extends AbstractRector
{
    /**
     * @return \Rector\Core\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Updates argument used in BCryptPasswordEncoder to be used with NativePasswordEncoder', [
            new CodeSample(
                <<<'PHP'
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
return new BCryptPasswordEncoder(12);
PHP,
                <<<'PHP'
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
return new NativePasswordEncoder(null, null, 12);
PHP
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [New_::class, MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\MethodCall $node
     *
     * @return \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\MethodCall|null
     */
    public function refactor(Node $node): ?Node
    {
        $expr = $node instanceof New_ ? $node->class : $node->var;

        if ($this->isObjectType($expr, 'Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder') || $this->isObjectType($expr, 'Symfony\Component\Security\Core\Encoder\NativePasswordEncoder')) {
            return $this->processArgumentPosition($node);
        }

        return null;
    }

    /**
     * @param \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\MethodCall $node
     *
     * @return \PhpParser\Node\Expr\New_|\PhpParser\Node\Expr\MethodCall|null
     */
    private function processArgumentPosition(Node $node): ?Node
    {
        $arguments = $node->args;
        if (count($arguments) === 0 || count($arguments) === 3) {
            return null;
        }

        $nullArgument = new Arg(new ConstFetch(new Name('null')));

        array_unshift($arguments, $nullArgument);
        array_unshift($arguments, $nullArgument);

        $node->args = $arguments;

        return $node;
    }
}
