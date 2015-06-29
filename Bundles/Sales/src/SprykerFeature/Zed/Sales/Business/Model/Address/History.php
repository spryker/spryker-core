<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

class SprykerFeature_Zed_Sales_Business_Model_Address_History
{

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressHistoryQuery
     */
    public function getQuery()
    {
        return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressHistoryQuery::create();
    }

    /**
     * @param \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress $addressEntity
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressHistory
     */
    public function addAddressToHistory($idAddress, \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress $addressEntity, $isBilling)
    {
        $entity = new \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressHistory();
        $entity->fromArray($addressEntity->toArray());
        $entity->setFkSalesOrderAddress($idAddress);
        $entity->setIsBilling($isBilling);
        $entity->save();
        return $entity;
    }

    /**
     * @param int $idSalesOrderAddress
     * @return PropelCollection
     */
    public function getBillingAddresses($idSalesOrderAddress)
    {
        return $this->getAddressesByType($idSalesOrderAddress, true);
    }

    /**
     * @param int $idSalesOrderAddress
     * @return PropelCollection
     */
    public function getShippingAddresses($idSalesOrderAddress)
    {
        return $this->getAddressesByType($idSalesOrderAddress, false);
    }

    /**
     * @param type $idSalesOrderAddress
     * @param type $isBilling
     * @return type
     */
    protected function getAddressesByType($idSalesOrderAddress, $isBilling)
    {
        $query = $this->getQuery();
        $addresses = $query->filterByFkSalesOrderAddress($idSalesOrderAddress)
                           ->filterByIsBilling($isBilling)
                           ->find();
        return $addresses;
    }
}
