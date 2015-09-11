<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Business\Api\Request\Partial;


use SprykerFeature\Zed\Payolution\Business\Api\Request\AbstractRequestExporter;

class Identification extends AbstractRequestExporter
{
    /**
     * @var string
     */
    protected $referenceID;

    /**
     * @var string
     */
    protected $transactionID;

    /**
     * @var string
     */
    protected $shopperID;

    /**
     * @var string
     */
    protected $uniqueID;

    /**
     * @return string
     */
    public function getReferenceID()
    {
        return $this->referenceID;
    }

    /**
     * @param string $referenceID
     */
    public function setReferenceID($referenceID)
    {
        $this->referenceID = $referenceID;
    }

    /**
     * @return string
     */
    public function getTransactionID()
    {
        return $this->transactionID;
    }

    /**
     * @param string $transactionID
     */
    public function setTransactionID($transactionID)
    {
        $this->transactionID = $transactionID;
    }

    /**
     * @return string
     */
    public function getShopperID()
    {
        return $this->shopperID;
    }

    /**
     * @param string $shopperID
     */
    public function setShopperID($shopperID)
    {
        $this->shopperID = $shopperID;
    }

    /**
     * @return string
     */
    public function getUniqueID()
    {
        return $this->uniqueID;
    }

    /**
     * @param string $uniqueID
     */
    public function setUniqueID($uniqueID)
    {
        $this->uniqueID = $uniqueID;
    }

}


