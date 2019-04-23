<?php declare(strict_types = 1);

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Router\Migrate\Yves\Migrator;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Stmt\ClassMethod;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

class RemoveConstructorRector extends AbstractRector
{
    /**
     * @return \Rector\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrates ControllerProvider namespace to RouteProviderPlugin namespace', [
            new CodeSample(
                'addXController',
                'addXRoute'
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [ClassMethod::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\ClassMethod|\PhpParser\Node\Expr\MethodCall|\PhpParser\Node $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if ((empty($node->getAttributes())) || !Strings::endsWith((string)$node->getAttribute('className'), 'ControllerProvider')) {
            return null;
        }

        if ($this->getName($node) === '__construct') {
            $this->removeNode($node);
        }

        return null;
    }
}
