<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Business\Model\CartGrouping;

use Generated\Shared\Cart\CartItemInterface;

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
     * @param CartItemInterface $cartItem
     *
     * @return string
     */
    public function build(CartItemInterface $cartItem)
    {
        if (empty($this->groupingProviderStack)) {
            return $this->getDefaultGroupingKey($cartItem);
        }

        $keyParts = $this->buildParts($cartItem);

        if (empty($keyParts)) {
            throw new \RuntimeException('There was no key parts provided for cart item grouping!');
        }

        return $this->combineParts($keyParts);
    }

    /**
     * @param CartItemInterface $cartItem
     *
     * @return string
     */
    protected function getDefaultGroupingKey(CartItemInterface $cartItem)
    {
        return $cartItem->getSku();
    }

    /**
     * @param CartItemInterface $cartItem
     *
     * @return array
     */
    protected function buildParts(CartItemInterface $cartItem)
    {
        $keyParts = [];
        foreach ($this->groupingProviderStack as $keyProvider) {
            $keyParts[] = $keyProvider->buildPart($cartItem);
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

    /**
     * @param GroupingProviderInterface $groupingProvider
     */
    public function addGroupingProvider(GroupingProviderInterface $groupingProvider)
    {
        $this->groupingProviderStack[] = $groupingProvider;
    }
}
