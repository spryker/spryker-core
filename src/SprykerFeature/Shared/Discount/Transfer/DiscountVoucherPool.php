<?php 

namespace SprykerFeature\Shared\Discount\Transfer;

use \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

/**
 * Class DiscountVoucherPool
 * @package SprykerFeature\Shared\Discount\Transfer
 */
class DiscountVoucherPool extends AbstractTransfer
{
    protected $idDiscountVoucherPool = null;

    protected $fkDiscountVoucherPoolCategory = null;

    protected $name = null;

    protected $template = null;

    protected $isActive = null;

    protected $isInfinitelyUsable = null;

    /**
     * @param $fkDiscountVoucherPoolCategory
     * @return $this
     */
    public function setFkDiscountVoucherPoolCategory($fkDiscountVoucherPoolCategory)
    {
        $this->fkDiscountVoucherPoolCategory = $fkDiscountVoucherPoolCategory;
        $this->addModifiedProperty('fkDiscountVoucherPoolCategory');

        return $this;
    }

    /**
     * @return null
     */
    public function getFkDiscountVoucherPoolCategory()
    {
        return $this->fkDiscountVoucherPoolCategory;
    }

    /**
     * @param $idDiscountVoucherPool
     * @return DiscountVoucherPool
     */
    public function setIdDiscountVoucherPool($idDiscountVoucherPool)
    {
        $this->idDiscountVoucherPool = $idDiscountVoucherPool;
        $this->addModifiedProperty('idDiscountVoucherPool');

        return $this;
    }

    /**
     * @return null
     */
    public function getIdDiscountVoucherPool()
    {
        return $this->idDiscountVoucherPool;
    }

    /**
     * @param $isActive
     * @return DiscountVoucherPool
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
        $this->addModifiedProperty('isActive');

        return $this;
    }

    /**
     * @return null
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param $isInfinitelyUsable
     * @return DiscountVoucherPool
     */
    public function setIsInfinitelyUsable($isInfinitelyUsable)
    {
        $this->isInfinitelyUsable = $isInfinitelyUsable;
        $this->addModifiedProperty('isInfinitelyUsable');

        return $this;
    }

    /**
     * @return null
     */
    public function getIsInfinitelyUsable()
    {
        return $this->isInfinitelyUsable;
    }

    /**
     * @param $name
     * @return DiscountVoucherPool
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

    /**
     * @param $template
     * @return DiscountVoucherPool
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        $this->addModifiedProperty('template');

        return $this;
    }

    /**
     * @return null
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
