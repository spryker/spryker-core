<?php declare(strict_types = 1);

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Router\Migrate\Yves\Migrator;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;
use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;

class ControllerProviderToRouteProviderClassNameRector extends AbstractRector
{
    /**
     * @return \Rector\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrates ControllerProvider to RouteProviderPlugin', [
            new CodeSample(
                'class ModuleControllerProvider extends AbstractYvesControllerProvider',
                'class ModuleRouteProviderPlugin extends AbstractRouteProviderPlugin'
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Class_|\PhpParser\Node $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!Strings::endsWith((string)$node->name, 'ControllerProvider')) {
            return null;
        }

        $extends = new FullyQualified(AbstractRouteProviderPlugin::class);
        $node->extends = $extends;

        $newClassName = new Identifier(str_replace('ControllerProvider', 'RouteProviderPlugin', (string)$node->name));
        $node->name = $newClassName;

        return $node;
    }
}
