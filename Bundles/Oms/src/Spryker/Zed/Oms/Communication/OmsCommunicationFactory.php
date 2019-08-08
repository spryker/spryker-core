<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Oms\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Oms\Communication\Builder\OmsTriggerFormCollectionBuilder;
use Spryker\Zed\Oms\Communication\Builder\OmsTriggerFormCollectionBuilderInterface;
use Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactory;
use Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface;
use Spryker\Zed\Oms\Communication\Table\TransitionLogTable;

/**
 * @method \Spryker\Zed\Oms\Persistence\OmsQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\Oms\OmsConfig getConfig()
 * @method \Spryker\Zed\Oms\Business\OmsFacadeInterface getFacade()
 * @method \Spryker\Zed\Oms\Persistence\OmsRepositoryInterface getRepository()
 */
class OmsCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Oms\Communication\Table\TransitionLogTable
     */
    public function createTransitionLogTable()
    {
        $queryContainer = $this->getQueryContainer();

        return new TransitionLogTable($queryContainer);
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\Factory\OmsTriggerFormFactoryInterface
     */
    public function createOmsTriggerFormFactory(): OmsTriggerFormFactoryInterface
    {
        return new OmsTriggerFormFactory($this->getFormFactory());
    }

    /**
     * @return \Spryker\Zed\Oms\Communication\Builder\OmsTriggerFormCollectionBuilderInterface
     */
    public function createOmsTriggerFormCollectionBuilder(): OmsTriggerFormCollectionBuilderInterface
    {
        return new OmsTriggerFormCollectionBuilder($this->createOmsTriggerFormFactory());
    }
}
