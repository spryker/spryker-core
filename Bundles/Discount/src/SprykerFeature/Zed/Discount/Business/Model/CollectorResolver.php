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

    const OPERATOR_OR = 'OR';

    /**
     * @var DiscountConfigInterface
     */
    protected $config;

    /**
     * @param DiscountConfigInterface $config
     */
    public function __construct(DiscountConfigInterface $config)
    {
        $this->config = $config;
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
            $collectorPlugin = $this->config->getCollectorPluginByName(
                $discountCollectorTransfer->getCollectorPlugin()
            );

            $itemsToCombine = $collectorPlugin->collect($discountTransfer, $container, $discountCollectorTransfer);

            if (!empty($collectedItems)) {
                $collectedItems = $this->combine($discountTransfer, $collectedItems, $itemsToCombine);
            } else {
                $collectedItems = $itemsToCombine;
            }
        }

        return $this->getUniqueDiscountableObjects($collectedItems);
    }

    /**
     * @param DiscountableInterface[] $discountableObjects
     *
     * @return DiscountableInterface[]
     */
    protected function getUniqueDiscountableObjects(array $discountableObjects)
    {
        $uniqueDiscountableObjects = [];
        foreach ($discountableObjects as $discountableObject) {
            $uniqueDiscountableObjects[spl_object_hash($discountableObject)] = $discountableObject;
        }

        return $uniqueDiscountableObjects;
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
    protected function combine(DiscountInterface $discountTransfer, $collectedItems, $itemsToCombine)
    {
        if ($discountTransfer->getCollectorLogicalOperator() === self::OPERATOR_OR) {
            return $this->combineWithOr($collectedItems, $itemsToCombine);
        } else {
            return $this->combineWithAnd($collectedItems, $itemsToCombine);
        }
    }

}
