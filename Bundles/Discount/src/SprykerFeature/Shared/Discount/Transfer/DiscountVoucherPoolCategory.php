<?php 

namespace SprykerFeature\Shared\Discount\Transfer;

use \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

/**
 * Class DiscountVoucherPoolCategory
 * @package SprykerFeature\Shared\Discount\Transfer
 */
class DiscountVoucherPoolCategory extends AbstractTransfer
{
    /**
     * @var int
     */
    protected $idDiscountVoucherPoolCategory = null;

    /**
     * @var int
     */
    protected $name = null;

    /**
     * @param $idDiscountVoucherPoolCategory
     * @return DiscountVoucherPoolCategory
     */
    public function setIdDiscountVoucherPoolCategory($idDiscountVoucherPoolCategory)
    {
        $this->idDiscountVoucherPoolCategory = $idDiscountVoucherPoolCategory;
        $this->addModifiedProperty('idDiscountVoucherPoolCategory');

        return $this;
    }

    /**
     * @return null
     */
    public function getIdDiscountVoucherPoolCategory()
    {
        return $this->idDiscountVoucherPoolCategory;
    }

    /**
     * @param $name
     * @return DiscountVoucherPoolCategory
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty('name');

        return $this;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }
}
