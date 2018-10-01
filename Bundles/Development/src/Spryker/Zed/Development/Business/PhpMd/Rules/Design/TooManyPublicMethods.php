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

class TooManyPublicMethods extends AbstractRule implements ClassAware
{
    public const NUMBER_OF_PUBLIC_METHODS = 'npm';
    public const THRESHOLD = 'maxmethods';

    /**
     * Regular expression that filters all methods that are ignored by this rule.
     *
     * @var string
     */
    private $ignoreRegexp;

    /**
     * This method checks the number of public methods with in a given class and checks
     * this number against a configured threshold.
     *
     * @param \PHPMD\Node\AbstractTypeNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $this->ignoreRegexp = $this->getStringProperty('ignorepattern');

        $threshold = $this->getIntProperty(static::THRESHOLD);
        if ($node->getMetric(static::NUMBER_OF_PUBLIC_METHODS) <= $threshold) {
            return;
        }

        $nom = $this->countMethods($node);
        if ($nom <= $threshold || $this->isIgnorable($node)) {
            return;
        }

        $this->addViolation($node, [
            $node->getType(),
            $node->getName(),
            $nom,
            $threshold,
        ]);
    }

    /**
     * Counts public methods within the given class/interface node.
     *
     * @param \PHPMD\Node\AbstractTypeNode $node
     *
     * @return int
     */
    private function countMethods(AbstractTypeNode $node)
    {
        $count = 0;
        foreach ($node->getMethods() as $method) {
            /** @var \PHPStan\Reflection\Php\PhpMethodReflection $node */
            $node = $method->getNode();
            if ($node->isPublic() && preg_match($this->ignoreRegexp, $method->getName()) === 0) {
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
        return (preg_match('/(Client|Yves|Zed)\\\\(.*?)\\\\(.*?)Facade/', $node->getFullQualifiedName()) || preg_match('/(Factory)/', $node->getName()));
    }
}
