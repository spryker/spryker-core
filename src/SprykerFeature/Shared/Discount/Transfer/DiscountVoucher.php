<?php 

namespace SprykerFeature\Shared\Discount\Transfer;

use \SprykerFeature\Shared\Library\TransferObject\AbstractTransfer;

/**
 * Class DiscountVoucher
 * @package SprykerFeature\Shared\Discount\Transfer
 */
class DiscountVoucher extends AbstractTransfer
{
    protected $idDiscountVoucher = null;

    protected $fkDiscountVoucherPool = null;

    protected $code = null;

    protected $isActive = null;

    /**
     * @param $code
     * @return DiscountVoucher
     */
    public function setCode($code)
    {
        $this->code = $code;
        $this->addModifiedProperty('code');

        return $this;
    }

    /**
     * @return null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $fkDiscountVoucherPool
     * @return DiscountVoucher
     */
    public function setFkDiscountVoucherPool($fkDiscountVoucherPool)
    {
        $this->fkDiscountVoucherPool = $fkDiscountVoucherPool;
        $this->addModifiedProperty('fkDiscountVoucherPool');

        return $this;
    }

    /**
     * @return null
     */
    public function getFkDiscountVoucherPool()
    {
        return $this->fkDiscountVoucherPool;
    }

    /**
     * @param $idDiscountVoucher
     * @return DiscountVoucher
     */
    public function setIdDiscountVoucher($idDiscountVoucher)
    {
        $this->idDiscountVoucher = $idDiscountVoucher;
        $this->addModifiedProperty('idDiscountVoucher');

        return $this;
    }

    /**
     * @return null
     */
    public function getIdDiscountVoucher()
    {
        return $this->idDiscountVoucher;
    }

    /**
     * @param $isActive
     * @return DiscountVoucher
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
}
