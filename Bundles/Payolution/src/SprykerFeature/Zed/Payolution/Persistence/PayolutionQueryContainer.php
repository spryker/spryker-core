<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Payolution\Persistence;


use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Payolution\PayolutionDependencyProvider;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;


/**
 * @method PayolutionPersistence getFactory()
 */
class PayolutionQueryContainer extends AbstractQueryContainer implements PayolutionQueryContainerInterface
{
    /**
     * @return SalesQueryContainerInterface
     */
    protected function getOsmQueryContainer()
    {
        return $this->getProvidedDepency(PayolutionDependencyProvider::QUERY_CONTAINER_OSM);
    }

    /**
     * @param int $idOrder
     *
     * @return SpySalesOrderQuery
     */
    public function querySalesOrderById($idOrder)
    {
        return $this->getOsmQueryContainer()->querySalesOrderById($idOrder);
    }


}
