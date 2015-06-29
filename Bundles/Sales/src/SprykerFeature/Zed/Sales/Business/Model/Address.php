<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

class SprykerFeature_Zed_Sales_Business_Model_Address
{

    /**
     * @return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressQuery
     */
    public function getQuery()
    {
        return \SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddressQuery::create();
    }

    /**
     * @param $addressId
     * @return mixed|SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress|SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderAddress[]
     */
    public function getAddressById($addressId)
    {
        return $this->getQuery()->findPk($addressId);
    }

}
