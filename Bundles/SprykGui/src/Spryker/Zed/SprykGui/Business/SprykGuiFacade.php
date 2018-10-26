<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business;

use Generated\Shared\Transfer\AccessibleTransferCollection;
use Generated\Shared\Transfer\ClassInformationTransfer;
use Generated\Shared\Transfer\ModuleCollectionTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationCollectionTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiBusinessFactory getFactory()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiRepositoryInterface getRepository()
 * @method \Spryker\Zed\SprykGui\Persistence\SprykGuiEntityManagerInterface getEntityManager()
 */
class SprykGuiFacade extends AbstractFacade implements SprykGuiFacadeInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return array
     */
    public function buildSprykView(string $sprykName, array $sprykArguments): array
    {
        return $this->getFactory()->createSpryk()->buildSprykView($sprykName, $sprykArguments);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return array
     */
    public function getSprykDefinitions(): array
    {
        return $this->getFactory()->createSpryk()->getSprykDefinitions();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sprykName
     *
     * @return string
     */
    public function drawSpryk(string $sprykName): string
    {
        return $this->getFactory()->createSpryk()->drawSpryk($sprykName);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $sprykName
     * @param array $sprykArguments
     *
     * @return string
     */
    public function runSpryk(string $sprykName, array $sprykArguments): string
    {
        return $this->getFactory()->createSpryk()->runSpryk($sprykName, $sprykArguments);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\ModuleCollectionTransfer
     */
    public function getModules(): ModuleCollectionTransfer
    {
        return $this->getFactory()->createModuleFinder()->findModules();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\OrganizationCollectionTransfer
     */
    public function getOrganizations(): OrganizationCollectionTransfer
    {
        return $this->getFactory()->createOrganizationFinder()->findOrganizations();
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $module
     *
     * @return \Generated\Shared\Transfer\AccessibleTransferCollection
     */
    public function getAccessibleTransfers(string $module): AccessibleTransferCollection
    {
        return $this->getFactory()->createAccessibleTransferFinder()->findAccessibleTransfers($module);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $className
     *
     * @return \Generated\Shared\Transfer\ClassInformationTransfer
     */
    public function getFactoryInformation(string $className): ClassInformationTransfer
    {
        return $this->getFactory()->createFactoryInformationFinder()->findFactoryInformation($className);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $spryk
     *
     * @return array
     */
    public function getSprykDefinitionByName(string $spryk): array
    {
        return $this->getFactory()->createSpryk()->getSprykDefinitionByName($spryk);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer
     */
    public function buildOptions(ModuleTransfer $moduleTransfer): ModuleTransfer
    {
        return $this->getFactory()->createOptionBuilder()->build($moduleTransfer);
    }

    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $choiceLoaderName
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(string $choiceLoaderName, ModuleTransfer $moduleTransfer): array
    {
        return $this->getFactory()->createChoiceLoader()->loadChoices($choiceLoaderName, $moduleTransfer);
    }
}
