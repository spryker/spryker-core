<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Business\Model;

use Generated\Shared\Discount\DiscountInterface;
use SprykerFeature\Zed\Calculation\Business\Model\CalculableInterface;
use SprykerFeature\Zed\Discount\DiscountConfigInterface;

class CollectorResolver
{
    const OPERATOR_AND = 'AND';
    const OPERATOR_OR = 'OR';

    /**
     * @var DiscountConfigInterface
     */
    protected $settings;

    /**
     * @param DiscountConfigInterface $settings
     */
    public function __construct(DiscountConfigInterface $settings)
    {
        $this->settings = $settings;
    }

    /**
     * @param CalculableInterface $container
     * @param DiscountInterface $discountTransfer
     *
     * @return DiscountableInterface[]
     */
    public function collectItems(CalculableInterface $container, DiscountInterface $discountTransfer)
    {
        $collectedItems = [];

        foreach ($discountTransfer->getDiscountCollectors() as $discountCollectorTransfer) {
            $collectorPlugin = $this->settings->getCollectorPluginByName(
                $discountCollectorTransfer->getCollectorPlugin()
            );

            $itemsToCombine = $collectorPlugin->collect($discountTransfer, $container, $discountCollectorTransfer);

            if (!empty($collectedItems)) {
                $collectedItems = $this->combine($discountTransfer, $collectedItems, $itemsToCombine);
            } else {
                $collectedItems = $itemsToCombine;
            }
        }

        $uniqDiscountableObjects = $this->getUniqDiscountableObjects($collectedItems);

        return $uniqDiscountableObjects;
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     *
     * @return array
     */
    protected function getUniqDiscountableObjects(array $discountableObjects)
    {
        $uniqDiscountableObjects = [];
        foreach ($discountableObjects as $discountableObject) {
            $uniqDiscountableObjects[spl_object_hash($discountableObject)] = $discountableObject;
        }

        return $uniqDiscountableObjects;
    }

    /**
     * @param DiscountableInterface[] $collectedItems
     * @param DiscountableInterface[] $itemsToCombine
     *
     * @return DiscountableInterface[]
     */
    protected function combineWithAnd(array $collectedItems, array $itemsToCombine)
    {
        $collectedItems = array_uintersect(
            $collectedItems,
            $itemsToCombine,
            function ($collected, $toCollect) {
                return strcmp(spl_object_hash($collected), spl_object_hash($toCollect));
            }
        );

        return $collectedItems;
    }

    /**
     * @param DiscountableInterface[] $collectedItems
     * @param DiscountableInterface[] $itemsToCombine
     *
     * @return DiscountableInterface[]
     */
    protected function combineWithOr($collectedItems, $itemsToCombine)
    {
        $collectedItems = array_merge($collectedItems, $itemsToCombine);

        return $collectedItems;
    }

    /**
     * @param DiscountInterface $discountTransfer
     * @param DiscountableInterface[] $collectedItems
     * @param DiscountableInterface[] $itemsToCombine
     *
     * @return DiscountableInterface[]
     */
    protected function combine(
        DiscountInterface $discountTransfer,
        $collectedItems,
        $itemsToCombine
    ) {
        if ($discountTransfer->getCollectorLogicalOperator() === self::OPERATOR_AND) {
            $collectedItems = $this->combineWithAnd($collectedItems, $itemsToCombine);
        }

        if ($discountTransfer->getCollectorLogicalOperator() === self::OPERATOR_OR) {
            $collectedItems = $this->combineWithOr($collectedItems, $itemsToCombine);
        }

        return $collectedItems;
    }
}
