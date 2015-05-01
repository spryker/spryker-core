<?php 

namespace SprykerFeature\Shared\Salesrule\Transfer;

/**
 *
 */
class Salesrule extends \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer
{

    protected $item = 'Salesrule\\Item';

    /**
     * @param \SprykerFeature\Shared\Salesrule\Transfer\Item $item
     * @return $this
     */
    public function setItem(\SprykerFeature\Shared\Salesrule\Transfer\Item $item)
    {
        $this->item = $item;
        $this->addModifiedProperty('item');
        return $this;
    }

    /**
     * @return \SprykerFeature\Shared\Salesrule\Transfer\Item
     */
    public function getItem()
    {
        return $this->item;
    }


}
