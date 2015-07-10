<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Ui\Dependency\Plugin;

use SprykerEngine\Zed\Kernel\Communication\AbstractPlugin;
use SprykerFeature\Zed\Ui\Business\Grid\StateContainer\Bean\StateContainerBeanTrait;
use SprykerFeature\Zed\Ui\Communication\UiDependencyContainer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

abstract class AbstractGridPlugin extends AbstractPlugin implements GridPluginInterface
{

    use StateContainerBeanTrait;

    /**
     * @var UiDependencyContainer
     */
    protected $dependencyContainer;

    /**
     * @param array $data
     *
     * @return array
     */
    public function getData(array $data)
    {
        return $data;
    }

    /**
     * @param ModelCriteria $query
     *
     * @return mixed|ModelCriteria
     */
    public function terminateQuery(ModelCriteria $query)
    {
        return $query;
    }

    /**
     * @param ModelCriteria $query
     *
     * @return mixed|ModelCriteria
     */
    public function specifyQuery(ModelCriteria $query)
    {
        return $query;
    }

}
