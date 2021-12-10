<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclMerchantPortal\Business;

use Spryker\Zed\AclMerchantPortal\AclMerchantPortalDependencyProvider;
use Spryker\Zed\AclMerchantPortal\Business\ConditionChecker\MerchantUser\UserRoleFilterConditionChecker;
use Spryker\Zed\AclMerchantPortal\Business\ConditionChecker\MerchantUser\UserRoleFilterConditionCheckerInterface;
use Spryker\Zed\AclMerchantPortal\Business\Expander\AclEntity\AclEntityMetadataConfigExpander;
use Spryker\Zed\AclMerchantPortal\Business\Expander\AclEntity\AclEntityMetadataConfigExpanderInterface;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGenerator;
use Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface;
use Spryker\Zed\AclMerchantPortal\Business\Writer\AclMerchantPortalWriter;
use Spryker\Zed\AclMerchantPortal\Business\Writer\AclMerchantPortalWriterInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface;
use Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AclMerchantPortal\AclMerchantPortalConfig getConfig()
 */
class AclMerchantPortalBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AclMerchantPortal\Business\Writer\AclMerchantPortalWriterInterface
     */
    public function createAclMerchantPortalWriter(): AclMerchantPortalWriterInterface
    {
        return new AclMerchantPortalWriter(
            $this->getAclFacade(),
            $this->getAclEntityFacade(),
            $this->createAclMerchantPortalGenerator(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\AclMerchantPortal\Business\Generator\AclMerchantPortalGeneratorInterface
     */
    public function createAclMerchantPortalGenerator(): AclMerchantPortalGeneratorInterface
    {
        return new AclMerchantPortalGenerator(
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\AclMerchantPortal\Business\Expander\AclEntity\AclEntityMetadataConfigExpanderInterface
     */
    public function createAclEntityMetadataConfigExpander(): AclEntityMetadataConfigExpanderInterface
    {
        return new AclEntityMetadataConfigExpander();
    }

    /**
     * @return \Spryker\Zed\AclMerchantPortal\Business\ConditionChecker\MerchantUser\UserRoleFilterConditionCheckerInterface
     */
    public function createUserRoleFilterConditionChecker(): UserRoleFilterConditionCheckerInterface
    {
        return new UserRoleFilterConditionChecker(
            $this->getConfig(),
            $this->getAclFacade(),
        );
    }

    /**
     * @return \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclFacadeInterface
     */
    public function getAclFacade(): AclMerchantPortalToAclFacadeInterface
    {
        return $this->getProvidedDependency(AclMerchantPortalDependencyProvider::FACADE_ACL);
    }

    /**
     * @return \Spryker\Zed\AclMerchantPortal\Dependency\Facade\AclMerchantPortalToAclEntityFacadeInterface
     */
    public function getAclEntityFacade(): AclMerchantPortalToAclEntityFacadeInterface
    {
        return $this->getProvidedDependency(AclMerchantPortalDependencyProvider::FACADE_ACL_ENTITY);
    }
}
