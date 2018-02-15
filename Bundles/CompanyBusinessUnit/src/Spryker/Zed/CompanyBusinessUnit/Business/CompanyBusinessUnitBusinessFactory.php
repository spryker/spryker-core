<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnit\Business;

use Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitReader;
use Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitReaderInterface;
use Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitWriter;
use Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitWriterInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitWriterRepositoryInterface;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\CompanyBusinessUnitPropelRepository;
use Spryker\Zed\CompanyBusinessUnit\Persistence\Propel\CompanyBusinessUnitWriterPropelRepository;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\CompanyBusinessUnit\CompanyBusinessUnitConfig getConfig()
 */
class CompanyBusinessUnitBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitWriterInterface
     */
    public function createCompanyBusinessUnitWriter(): CompanyBusinessUnitWriterInterface
    {
        return new CompanyBusinessUnitWriter(
            $this->createCompanyBusinessUnitWriterRepository(),
            $this->createCompanyBusinessUnitRepository()
        );
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\Model\CompanyBusinessUnitReaderInterface
     */
    public function createCompanyBusinessUnitReader(): CompanyBusinessUnitReaderInterface
    {
        return new CompanyBusinessUnitReader($this->createCompanyBusinessUnitRepository());
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitWriterRepositoryInterface
     */
    protected function createCompanyBusinessUnitWriterRepository(): CompanyBusinessUnitWriterRepositoryInterface
    {
        return new CompanyBusinessUnitWriterPropelRepository();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Persistence\CompanyBusinessUnitRepositoryInterface
     */
    protected function createCompanyBusinessUnitRepository(): CompanyBusinessUnitRepositoryInterface
    {
        return new CompanyBusinessUnitPropelRepository();
    }
}
