<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PropelOrm\Business\Builder\Collector;

/**
 * @method \Spryker\Zed\PropelOrm\Business\PropelOrmBusinessFactory getFactory()
 */
class ClassNamespacesCollector implements ClassNamespacesCollectorInterface
{
    /**
     * @var array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\DeclareClassesToBeUsedInterface>
     */
    protected $pluginStack;

    /**
     * @param array<\Spryker\Zed\PropelOrmExtension\Dependency\Plugin\DeclareClassesToBeUsedInterface> $pluginStack
     */
    public function __construct(array $pluginStack)
    {
        $this->pluginStack = $pluginStack;
    }

    /**
     * @return array<string>
     */
    public function extractClassesToDeclare(): array
    {
        $classes = [];
        foreach ($this->pluginStack as $plugin) {
            $classes = array_merge($classes, $plugin->getClassesToDeclare());
        }

        return array_unique($classes);
    }
}
