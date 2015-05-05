<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerEngine\Shared\Transfer\TransferInterface;

interface DiscountTotalItemInterface extends TransferInterface
{
    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $discountType
     *
     * @return $this
     */
    public function setDiscountType($discountType);

    /**
     * @return string
     */
    public function getDiscountType();

    /**
     * @param int $amount
     *
     * @return $this
     */
    public function setAmount($amount);

    /**
     * @return int
     */
    public function getAmount();

    /**
     * @param array $codes
     *
     * @return $this
     */
    public function setCodes(array $codes);

    /**
     * @return array
     */
    public function getCodes();

    /**
     * @param string $code
     *
     * @return $this
     */
    public function addCode($code);
}
