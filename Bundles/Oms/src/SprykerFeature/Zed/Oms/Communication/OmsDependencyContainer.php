<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Oms\Communication;

use SprykerFeature\Zed\Oms\Communication\Table\TransitionLogTable;
use Generated\Zed\Ide\FactoryAutoCompletion\OmsCommunication;
use SprykerEngine\Zed\Kernel\Communication\AbstractCommunicationDependencyContainer;
use SprykerFeature\Zed\Oms\Persistence\OmsQueryContainerInterface;

/**
 * @method OmsCommunication getFactory()
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
