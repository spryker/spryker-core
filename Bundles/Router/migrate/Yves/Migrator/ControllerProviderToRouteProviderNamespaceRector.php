<?php declare(strict_types = 1);

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Router\Migrate\Yves\Migrator;

use Nette\Utils\Strings;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Namespace_;
use Rector\Rector\AbstractRector;
use Rector\RectorDefinition\CodeSample;
use Rector\RectorDefinition\RectorDefinition;

class ControllerProviderToRouteProviderNamespaceRector extends AbstractRector
{
    /**
     * @return \Rector\RectorDefinition\RectorDefinition
     */
    public function getDefinition(): RectorDefinition
    {
        return new RectorDefinition('Migrates ControllerProvider namespace to RouteProviderPlugin namespace', [
            new CodeSample(
                'namespace Spryker\Yves\Module\Plugin\Provider\SubDirectory;',
                'namespace Spryker\Yves\Module\Plugin\Provider\SubDirectory;'
            ),
        ]);
    }

    /**
     * @return string[]
     */
    public function getNodeTypes(): array
    {
        return [Namespace_::class];
    }

    /**
     * @param \PhpParser\Node\Stmt\Namespace__|\PhpParser\Node $node
     *
     * @return \PhpParser\Node|null
     */
    public function refactor(Node $node): ?Node
    {
        if (!Strings::endsWith((string)$node->getAttribute('fileInfo')->getFileName(), 'ControllerProvider')) {
            $nodeNameParts = $node->name->parts;
            $positionOfProvider = array_search('Provider', $nodeNameParts);
            $nodeNameParts[$positionOfProvider] = 'Router';
            $node->name = new Name(implode('\\', $nodeNameParts));

            return $node;
        }

        return null;
    }
}
