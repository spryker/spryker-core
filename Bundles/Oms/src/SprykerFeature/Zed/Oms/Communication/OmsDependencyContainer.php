<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Oms\Communication;

use Spryker\Zed\Oms\Communication\Table\TransitionLogTable;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface;

/**
 * @method OmsQueryContainerInterface getQueryContainer()
 */
class OmsDependencyContainer extends AbstractCommunicationDependencyContainer
{

    /**
     * @return Table\TransitionLogTable
     */
    public function createTransitionLogTable()
    {
        $queryContainer = $this->getQueryContainer();

        return new TransitionLogTable($queryContainer);
    }

}
