<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReclamation\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationMapper;
use Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationMapperInterface;
use Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationReader;
use Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationReaderInterface;
use Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationWriter;
use Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationWriterInterface;
use Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface;
use Spryker\Zed\SalesReclamation\SalesReclamationDependencyProvider;

/**
 * @method \Spryker\Zed\SalesReclamation\SalesReclamationConfig getConfig()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\SalesReclamation\Persistence\SalesReclamationRepositoryInterface getRepository()
 */
class SalesReclamationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationWriterInterface
     */
    public function createReclamationWriter(): ReclamationWriterInterface
    {
        return new ReclamationWriter($this->getEntityManager(), $this->createReclamationMapper());
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationReaderInterface
     */
    public function createReclamationReader(): ReclamationReaderInterface
    {
        return new ReclamationReader($this->getRepository(), $this->getSalesFacade());
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Dependency\Facade\SalesReclamationToSalesFacadeInterface
     */
    public function getSalesFacade(): SalesReclamationToSalesFacadeInterface
    {
        return $this->getProvidedDependency(SalesReclamationDependencyProvider::FACADE_SALES);
    }

    /**
     * @return \Spryker\Zed\SalesReclamation\Business\Reclamation\ReclamationMapperInterface
     */
    public function createReclamationMapper(): ReclamationMapperInterface
    {
        return new ReclamationMapper();
    }
}
