<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ApplicationTransfer;
use Generated\Shared\Transfer\ModuleFilterTransfer;
use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This type can be used to select a module from a given list.
 *
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 * @method \Spryker\Zed\SprykGui\SprykGuiConfig getConfig()
 * @method \Spryker\Zed\SprykGui\Communication\SprykGuiCommunicationFactory getFactory()
 */
class ModuleChoiceType extends AbstractType
{
    protected const ORGANIZATION = 'organization';
    protected const APPLICATION = 'application';
    protected const MODULE = 'module';
    protected const MODULE_FILTER = 'moduleFilter';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault(static::MODULE_FILTER, []);

        $resolver->setDefaults([
            'choices' => function (Options $options) {
                if ($options->offsetExists(static::MODULE_FILTER)) {
                    return $this->getFilteredModules($options->offsetGet(static::MODULE_FILTER));
                }

                return $this->getFacade()->getModules();
            },
            'choice_label' => function (ModuleTransfer $moduleTransfer) {
                return $moduleTransfer->getName();
            },
            'choice_value' => function (ModuleTransfer $moduleTransfer) {
                return $moduleTransfer->getName();
            },
            'group_by' => function (ModuleTransfer $moduleTransfer) {
                return $moduleTransfer->getOrganization()->getName();
            },
            'data_class' => ModuleTransfer::class,
            'placeholder' => 'Select a module',
        ]);
    }

    /**
     * @return string
     */
    public function getParent(): string
    {
        return ChoiceType::class;
    }

    /**
     * @param array $moduleFilter
     *
     * @return \Generated\Shared\Transfer\ModuleTransfer[]
     */
    protected function getFilteredModules(array $moduleFilter): array
    {
        $moduleFilterTransfer = new ModuleFilterTransfer();
        if (isset($moduleFilter[static::ORGANIZATION])) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($moduleFilter[static::ORGANIZATION]);
            $moduleFilterTransfer->setOrganization($organizationTransfer);
        }

        if (isset($moduleFilter[static::APPLICATION])) {
            $applicationTransfer = new ApplicationTransfer();
            $applicationTransfer->setName($moduleFilter[static::APPLICATION]);
            $moduleFilterTransfer->setApplication($applicationTransfer);
        }

        if (isset($moduleFilter[static::MODULE])) {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleFilter[static::MODULE]);
            $moduleFilterTransfer->setModule($moduleTransfer);
        }

        return $this->getFacade()->getModules($moduleFilterTransfer);
    }
}
