<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business;

use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator\CompanyBusinessUnitCreator;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitCreator\CompanyBusinessUnitCreatorInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriterWriter;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface getRepository()
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitWriter\CompanyBusinessUnitWriterInterface
     */
    public function createCompanyBusinessUnitWriter(): CompanyBusinessUnitWriterInterface
    {
        return new CompanyBusinessUnitWriterWriter(
            $this->getRepository(),
            $this->getEntityManager()
        );
    }

    /**
     * @return CompanyBusinessUnitCreatorInterface
     */
    public function createCompanyBusinessUnitCreator(): CompanyBusinessUnitCreatorInterface
    {
        return new CompanyBusinessUnitCreator(
            $this->getEntityManager(),
            $this->getConfig()
        );
    }
}
