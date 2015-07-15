<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Cart\Communication\Plugin;

use SprykerFeature\Zed\Cart\Dependency\ItemExpanderPluginInterface;
use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use Generated\Shared\Cart\ChangeInterface;
use SprykerFeature\Zed\Cart\Business\CartFacade;


/**
* @method CartFacade getFacade()
*/
class GroupKeyExpanderPlugin extends AbstractPlugin implements ItemExpanderPluginInterface
{
    /**
     * @param ChangeInterface $change
     *
     * @return ChangeInterface
     */
    public function expandItems(ChangeInterface $change)
    {
        foreach ($change->getItems() as $cartItem) {
            $groupKey = $this->getFacade()->buildGroupKey($cartItem);
            $cartItem->setGroupKey($groupKey);
        }

        return $change;
    }
}
