<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AclEntity\Persistence\Propel\Behavior;

use Propel\Generator\Model\Behavior;
use Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface;
use Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory;
use Spryker\Zed\Kernel\BundleConfigResolverAwareTrait;
use Spryker\Zed\Kernel\ClassResolver\Persistence\PersistenceFactoryResolver;
use Spryker\Zed\Kernel\Locator;

/**
 * @method \Spryker\Zed\AclEntity\AclEntityConfig getConfig()
 */
class AclEntityBehavior extends Behavior
{
    use BundleConfigResolverAwareTrait;

    /**
     * @var string
     */
    protected const ACTION_SELECT = 'Select';

    /**
     * @var string
     */
    protected const ACTION_UPDATE = 'Update';

    /**
     * @var string
     */
    protected const ACTION_CREATE = 'Create';

    /**
     * @var string
     */
    protected const ACTION_DELETE = 'Delete';

    /**
     * @return string
     */
    public function objectMethods(): string
    {
        $script = '';
        $script .= $this->addGetPersistenceFactoryMethod();

        return $script;
    }

    /**
     * @return string
     */
    public function queryMethods(): string
    {
        $script = '';
        $script .= $this->addGetPersistenceFactoryMethod();
        $script .= $this->addIsSegmentQueryMethod();

        return $script;
    }

    /**
     * @return string
     */
    public function preUpdate(): string
    {
        return $this->renderTemplate('modelPreHook', ['action' => static::ACTION_UPDATE]);
    }

    /**
     * @return string
     */
    public function preInsert(): string
    {
        return $this->renderTemplate('modelPreHook', ['action' => static::ACTION_CREATE]);
    }

    /**
     * @return string
     */
    public function preSelectQuery(): string
    {
        return $this->renderTemplate('queryPreHook', ['action' => static::ACTION_SELECT]);
    }

    /**
     * @return string
     */
    public function preUpdateQuery(): string
    {
        return $this->renderTemplate('queryPreHook', ['action' => static::ACTION_UPDATE]);
    }

    /**
     * @return string
     */
    public function preDeleteQuery(): string
    {
        return $this->renderTemplate('queryPreHook', ['action' => static::ACTION_DELETE]);
    }

    /**
     * @return void
     */
    public function modifyTable(): void
    {
        $aclEntityMetadataConfigTransfer = $this->getAclEntityFacade()->getAclEntityMetadataConfig(false);
        $aclEntityMetadataReader = $this->getAclEntityPersistenceFactory()->createAclEntityMetadataReader(
            $aclEntityMetadataConfigTransfer,
        );
        $aclEntityMetadataTransfer = $aclEntityMetadataReader->findAclEntityMetadataTransferForEntityClass(
            $this->getEntityName(),
        );

        if (!$aclEntityMetadataTransfer || !$aclEntityMetadataTransfer->getHasSegmentTable()) {
            return;
        }

        $table = $this->getTableOrFail();
        $database = $table->getDatabaseOrFail();

        $connectorTableName = $this->getAclEntityPersistenceFactory()
            ->getAclEntityService()
            ->generateSegmentConnectorTableName($table->getName());
        if (!$database->hasTable($connectorTableName)) {
            $connectorTableBuilder = $this->getAclEntityPersistenceFactory()->createConnectorTableBuilder(
                $this->getTableOrFail(),
                $database,
            );
            $connectorTableBuilder->build();
        }
    }

    /**
     * @return string
     */
    protected function addGetPersistenceFactoryMethod(): string
    {
        return $this->renderTemplate('objectGetAclPersistenceFactory');
    }

    /**
     * @return string
     */
    protected function addIsSegmentQueryMethod(): string
    {
        return $this->renderTemplate('queryIsSegmentQuery');
    }

    /**
     * @return string
     */
    protected function getEntityName(): string
    {
        return sprintf('%s\%s', $this->getTableOrFail()->getNamespace(), $this->getTableOrFail()->getPhpName());
    }

    /**
     * @return \Spryker\Zed\AclEntity\Business\AclEntityFacadeInterface
     */
    protected function getAclEntityFacade(): AclEntityFacadeInterface
    {
        /** @var \Generated\Zed\Ide\AutoCompletion&\Spryker\Shared\Kernel\LocatorLocatorInterface $locator */
        $locator = Locator::getInstance();

        return $locator->aclEntity()->facade();
    }

    /**
     * @return \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory
     */
    protected function getAclEntityPersistenceFactory(): AclEntityPersistenceFactory
    {
        /** @var \Spryker\Zed\AclEntity\Persistence\AclEntityPersistenceFactory $aclEntityPersistenceFactory */
        $aclEntityPersistenceFactory = (new PersistenceFactoryResolver())->resolve($this);

        return $aclEntityPersistenceFactory;
    }
}
