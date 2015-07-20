<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Model\ItemGrouping;

use Generated\Shared\Cart\GroupKeyParameterInterface;

class KeyBuilder
{
    const KEY_PART_SEPARATOR = '_';

    /**
     * @var GroupingProviderInterface[]
     */
    private $groupingProviderStack = [];

    /**
     * KeyBuilder constructor.
     *
     * @param GroupingProviderInterface[] $groupingProviderStack
     */
    public function __construct(array $groupingProviderStack)
    {
        $this->groupingProviderStack = $groupingProviderStack;
    }

    /**
     * @param GroupKeyParameterInterface $groupKeyParameters
     *
     * @return string
     */
    public function build(GroupKeyParameterInterface $groupKeyParameters)
    {
        $keyParts = $this->buildParts($groupKeyParameters);

        if (empty($keyParts)) {
            throw new \RuntimeException('There was no key parts provided for cart item grouping!');
        }

        return $this->combineParts($keyParts);
    }

    /**
     * @param GroupKeyParameterInterface $groupKeyParameters
     *
     * @return array
     */
    protected function buildParts(GroupKeyParameterInterface $groupKeyParameters)
    {
        $keyParts = [];
        foreach ($this->groupingProviderStack as $keyProvider) {
            $keyParts[] = $keyProvider->buildPart($groupKeyParameters);
        }
        return $keyParts;
    }

    /**
     * @param array $keyParts
     *
     * @return string
     */
    private function combineParts(array $keyParts)
    {
        return implode(self::KEY_PART_SEPARATOR, $keyParts);
    }
}
