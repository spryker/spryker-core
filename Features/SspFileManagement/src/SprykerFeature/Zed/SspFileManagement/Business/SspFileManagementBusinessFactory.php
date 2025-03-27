<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspFileManagement\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerFeature\Zed\SspFileManagement\Business\DashboardDataProvider\FileDashboardDataProvider;
use SprykerFeature\Zed\SspFileManagement\Business\DashboardDataProvider\FileDashboardDataProviderInterface;
use SprykerFeature\Zed\SspFileManagement\Business\Deleter\FileAttachmentDeleter;
use SprykerFeature\Zed\SspFileManagement\Business\Deleter\FileAttachmentDeleterInterface;
use SprykerFeature\Zed\SspFileManagement\Business\Reader\FileReader;
use SprykerFeature\Zed\SspFileManagement\Business\Reader\FileReaderInterface;
use SprykerFeature\Zed\SspFileManagement\Business\Saver\FileAttachmentSaver;
use SprykerFeature\Zed\SspFileManagement\Business\Saver\FileAttachmentSaverInterface;
use SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\CompanyBusinessUnitFileQueryStrategy;
use SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\CompanyFileQueryStrategy;
use SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\CompanyUserFileQueryStrategy;
use SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface;

/**
 * @method \SprykerFeature\Zed\SspFileManagement\SspFileManagementConfig getConfig()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementEntityManagerInterface getEntityManager()
 * @method \SprykerFeature\Zed\SspFileManagement\Persistence\SspFileManagementRepositoryInterface getRepository()
 */
class SspFileManagementBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Business\Deleter\FileAttachmentDeleterInterface
     */
    public function createFileAttachmentDeleter(): FileAttachmentDeleterInterface
    {
        return new FileAttachmentDeleter($this->getEntityManager());
    }

    /**
     * @return array<\SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface>
     */
    public function createFileQueryStrategies(): array
    {
        return [
            $this->createCompanyUserFileQueryStrategy(),
            $this->createCompanyFileQueryStrategy(),
            $this->createCompanyBusinessUnitFileQueryStrategy(),
        ];
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface
     */
    public function createCompanyUserFileQueryStrategy(): FilePermissionQueryStrategyInterface
    {
        return new CompanyUserFileQueryStrategy();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface
     */
    public function createCompanyFileQueryStrategy(): FilePermissionQueryStrategyInterface
    {
        return new CompanyFileQueryStrategy();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Persistence\QueryStrategy\FilePermissionQueryStrategyInterface
     */
    public function createCompanyBusinessUnitFileQueryStrategy(): FilePermissionQueryStrategyInterface
    {
        return new CompanyBusinessUnitFileQueryStrategy();
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Business\Reader\FileReaderInterface
     */
    public function createFileReader(): FileReaderInterface
    {
        return new FileReader(
            $this->getRepository(),
            $this->createFileQueryStrategies(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Business\Saver\FileAttachmentSaverInterface
     */
    public function createFileAttachmentSaver(): FileAttachmentSaverInterface
    {
        return new FileAttachmentSaver(
            $this->getEntityManager(),
            $this->getRepository(),
        );
    }

    /**
     * @return \SprykerFeature\Zed\SspFileManagement\Business\DashboardDataProvider\FileDashboardDataProviderInterface
     */
    public function createDashboardDataProvider(): FileDashboardDataProviderInterface
    {
        return new FileDashboardDataProvider($this->createFileReader(), $this->getConfig());
    }
}
