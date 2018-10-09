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
 */
class ModuleChoiceType extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('moduleFilter', []);

        $resolver->setDefaults([
            'choices' => function (Options $options) {
                if ($options->offsetExists('moduleFilter')) {
                    return $this->getFilteredModules($options->offsetGet('moduleFilter'));
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
        if (isset($moduleFilter['organization'])) {
            $organizationTransfer = new OrganizationTransfer();
            $organizationTransfer->setName($moduleFilter['organization']);
            $moduleFilterTransfer->setOrganization($organizationTransfer);
        }
        if (isset($moduleFilter['application'])) {
            $applicationTransfer = new ApplicationTransfer();
            $applicationTransfer->setName($moduleFilter['application']);
            $moduleFilterTransfer->setApplication($applicationTransfer);
        }
        if (isset($moduleFilter['module'])) {
            $moduleTransfer = new ModuleTransfer();
            $moduleTransfer->setName($moduleFilter['module']);
            $moduleFilterTransfer->setModule($moduleTransfer);
        }

        return $this->getFacade()->getModules($moduleFilterTransfer);
    }
}
