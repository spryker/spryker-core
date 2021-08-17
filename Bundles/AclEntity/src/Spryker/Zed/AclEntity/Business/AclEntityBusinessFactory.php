<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Business;

use Spryker\Service\AclEntity\AclEntityServiceInterface;
use Spryker\Zed\AclEntity\AclEntityDependencyProvider;
use Spryker\Zed\AclEntity\Business\Expander\AclRolesExpander;
use Spryker\Zed\AclEntity\Business\Expander\AclRolesExpanderInterface;
use Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReader;
use Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReaderInterface;
use Spryker\Zed\AclEntity\Business\Reader\AclEntityReader;
use Spryker\Zed\AclEntity\Business\Reader\AclEntityReaderInterface;
use Spryker\Zed\AclEntity\Business\Writer\AclEntityRuleWriter;
use Spryker\Zed\AclEntity\Business\Writer\AclEntityRuleWriterInterface;
use Spryker\Zed\AclEntity\Business\Writer\AclEntitySegmentWriter;
use Spryker\Zed\AclEntity\Business\Writer\AclEntitySegmentWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityEntityManagerInterface getEntityManager()
 * @method \Spryker\Zed\AclEntity\Persistence\AclEntityRepositoryInterface getRepository()
 */
class AclEntityBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\AclEntity\Business\Writer\AclEntitySegmentWriterInterface
     */
    public function createAclEntitySegmentWriter(): AclEntitySegmentWriterInterface
    {
        return new AclEntitySegmentWriter(
            $this->getEntityManager(),
            $this->getAclEntityService()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Business\Writer\AclEntityRuleWriterInterface
     */
    public function createAclEntityRuleWriter(): AclEntityRuleWriterInterface
    {
        return new AclEntityRuleWriter(
            $this->getEntityManager()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Business\Expander\AclRolesExpanderInterface
     */
    public function createAclRolesExpander(): AclRolesExpanderInterface
    {
        return new AclRolesExpander(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Business\Reader\AclEntityMetadataConfigReaderInterface
     */
    public function createAclEntityMetadataConfigReader(): AclEntityMetadataConfigReaderInterface
    {
        return new AclEntityMetadataConfigReader(
            $this->getAclEntityMetadataCollectionExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntity\Business\Reader\AclEntityReaderInterface
     */
    public function createAclEntityReader(): AclEntityReaderInterface
    {
        return new AclEntityReader(
            $this->getIsAclEntityEnabled(),
            $this->getAclEntityDisablerPlugins()
        );
    }

    /**
     * @return \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityMetadataConfigExpanderPluginInterface[]
     */
    public function getAclEntityMetadataCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(
            AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_METADATA_COLLECTION_EXPANDER
        );
    }

    /**
     * @return \Spryker\Zed\AclEntityExtension\Dependency\Plugin\AclEntityDisablerPluginInterface[]
     */
    public function getAclEntityDisablerPlugins(): array
    {
        return $this->getProvidedDependency(
            AclEntityDependencyProvider::PLUGINS_ACL_ENTITY_DISABLER
        );
    }

    /**
     * @return \Spryker\Service\AclEntity\AclEntityServiceInterface
     */
    public function getAclEntityService(): AclEntityServiceInterface
    {
        return $this->getProvidedDependency(AclEntityDependencyProvider::SERVICE_ACL_ENTITY);
    }

    /**
     * @return bool
     */
    public function getIsAclEntityEnabled(): bool
    {
        return $this->getProvidedDependency(AclEntityDependencyProvider::PARAM_IS_ACL_ENTITY_ENABLED);
    }
}
