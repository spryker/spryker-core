<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd\Rules\Design;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Node\AbstractTypeNode;
use PHPMD\Rule\ClassAware;

class TooManyMethods extends AbstractRule implements ClassAware
{
    public const MAX_METHODS = 'maxmethods';
    public const METHODS_IGNORE_PATTERN = 'ignorepattern';

    /**
     * Regular expression that filters all methods that are ignored by this rule.
     *
     * @var string
     */
    private $ignoreRegexp;

    /**
     * This method checks the number of methods with in a given class and checks
     * this number against a configured threshold.
     *
     * @param \PHPMD\Node\AbstractTypeNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $this->ignoreRegexp = $this->getStringProperty(static::METHODS_IGNORE_PATTERN);

        $threshold = $this->getIntProperty(static::MAX_METHODS);
        if ($node->getMetric('nom') <= $threshold) {
            return;
        }

        $numberOfMethods = $this->countMethods($node);
        if ($numberOfMethods <= $threshold || $this->isIgnorable($node)) {
            return;
        }

        $this->addViolation($node, [
            $node->getType(),
            $node->getName(),
            $numberOfMethods,
            $threshold,
        ]);
    }

    /**
     * Counts all methods within the given class/interface node.
     *
     * @param \PHPMD\Node\AbstractTypeNode $node
     *
     * @return int
     */
    private function countMethods(AbstractTypeNode $node)
    {
        $count = 0;
        foreach ($node->getMethodNames() as $name) {
            if (preg_match($this->ignoreRegexp, $name) === 0) {
                ++$count;
            }
        }

        return $count;
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return bool
     */
    private function isIgnorable(AbstractNode $node)
    {
        return (
            preg_match('/(Client|Yves|Zed)\\\\(.*?)\\\\(.*?)Facade/', $node->getFullQualifiedName())
            || preg_match('/(BusinessFactory|CommunicationFactory)/', $node->getName())
        );
    }
}
