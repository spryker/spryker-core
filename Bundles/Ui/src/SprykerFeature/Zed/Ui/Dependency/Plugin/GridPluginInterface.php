<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Dependency\Plugin;

use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\Bean\StateContainerBeanInterface;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface GridPluginInterface extends StateContainerBeanInterface
{

    /**
     * @param array $data
     *
     * @return array
     */
    public function getData(array $data);

    /**
     * @param ModelCriteria $query
     *
     * @return mixed
     */
    public function terminateQuery(ModelCriteria $query);

    /**
     * @param ModelCriteria $query
     *
     * @return mixed
     */
    public function specifyQuery(ModelCriteria $query);

}
