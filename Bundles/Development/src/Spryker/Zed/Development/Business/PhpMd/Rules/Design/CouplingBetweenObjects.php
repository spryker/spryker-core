<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd\Rules\Design;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class CouplingBetweenObjects extends AbstractRule implements ClassAware
{
    public const COUPLING_BETWEEN_OBJECTS = 'cbo';
    public const THRESHOLD = 'minimum';

    /**
     * This method should implement the violation analysis algorithm of concrete
     * rule implementations. All extending classes must implement this method.
     *
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $couplingBetweenObjects = $node->getMetric(static::COUPLING_BETWEEN_OBJECTS);

        $threshold = $this->getIntProperty(static::THRESHOLD);
        if ($couplingBetweenObjects >= $threshold && !$this->isIgnorable($node)) {
            $this->addViolation($node, [$node->getName(), $couplingBetweenObjects, $threshold]);
        }
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    private function isIgnorable(AbstractNode $node)
    {
        return (
            preg_match('/(Client|Yves|Zed)\\\\(.*?)\\\\(.*?)(DependencyProvider|Factory|ServiceProvider)/', $node->getFullQualifiedName())
            || ($node->getName() === 'YvesBootstrap')
        );
    }
}
