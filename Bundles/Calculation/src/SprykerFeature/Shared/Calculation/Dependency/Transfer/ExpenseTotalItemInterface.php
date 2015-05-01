<?php

namespace SprykerFeature\Shared\Calculation\Dependency\Transfer;

use SprykerFeature\Shared\Library\TransferObject\TransferInterface;

interface ExpenseTotalItemInterface extends TransferInterface
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
     * @param string $type
     *
     * @return $this
     */
    public function setType($type);

    /**
     * @return string
     */
    public function getType();

    /**
     * @param int $grossPrice
     *
     * @return $this
     */
    public function setGrossPrice($grossPrice);

    /**
     * @return int
     */
    public function getGrossPrice();

    /**
     * @param int $priceToPay
     *
     * @return $this
     */
    public function setPriceToPay($priceToPay);

    /**
     * @return int
     */
    public function getPriceToPay();
}