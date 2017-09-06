<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\PhpMd\Rules\Design;

use PHPMD\AbstractNode;
use PHPMD\AbstractRule;
use PHPMD\Rule\ClassAware;

class NumberOfChildren extends AbstractRule implements ClassAware
{

    const NUMBER_OF_CHILDREN = 'nocc';
    const THRESHOLD = 'minimum';
    const CUSTOM_THRESHOLDS = 'customMinimum';

    /**
     * This method checks the number of classes derived from the given class
     * node.
     *
     * @param \PHPMD\AbstractNode $node
     *
     * @return void
     */
    public function apply(AbstractNode $node)
    {
        $numberOfChildren = $node->getMetric(static::NUMBER_OF_CHILDREN);
        $threshold = $this->getThreshold($node);
        if ($numberOfChildren >= $threshold && !$this->isIgnorable($node)) {
            $this->addViolation($node, [
                $node->getType(),
                $node->getName(),
                $numberOfChildren,
                $threshold,
            ]);
        }
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return int
     */
    private function getThreshold(AbstractNode $node)
    {
        $fullyQualifiedName = $node->getFullQualifiedName();
        $customThresholds = $this->getCustomThresholds();

        if (array_key_exists($fullyQualifiedName, $customThresholds)) {
            return $customThresholds[$fullyQualifiedName];
        }

        return $this->getIntProperty(static::THRESHOLD);
    }

    /**
     * @return array
     */
    private function getCustomThresholds()
    {
        return json_decode($this->getStringProperty(static::CUSTOM_THRESHOLDS), true);
    }

    /**
     * @param \PHPMD\AbstractNode $node
     *
     * @return int
     */
    private function isIgnorable(AbstractNode $node)
    {
        $fullyQualifiedClassName = $node->getFullQualifiedName();

        return preg_match('/Zed\\\\Importer\\\\Business\\\\(Importer|Installer)\\\\Abstract(Importer|Installer)/', $fullyQualifiedClassName);
    }

}
