<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Communication\Form\Type;

use Generated\Shared\Transfer\ModuleTransfer;
use Generated\Shared\Transfer\OrganizationCollectionTransfer;
use Generated\Shared\Transfer\OrganizationTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Spryker\Zed\SprykGui\Business\SprykGuiFacadeInterface getFacade()
 */
class NewModuleType extends AbstractType
{
    protected const SPRYK_DEFINITION = 'sprykDefinition';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            static::SPRYK_DEFINITION,
        ]);

        $resolver->setDefaults([
            'data_class' => ModuleTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $sprykDefinition = $options[static::SPRYK_DEFINITION];

        $builder->add('name', TextType::class);
//        $builder->add('organization', ChoiceType::class, [
//
//            'data' => $sprykDefinition['arguments']['moduleOrganization']['default'],
//        ]);

        $organizationCollection = new OrganizationCollectionTransfer();
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName('Spryker')
            ->setRootPath(APPLICATION_ROOT_DIR . 'vendor/spryker/spryker/');

        $organizationCollection->addOrganization($organizationTransfer);
        $organizationTransfer = new OrganizationTransfer();
        $organizationTransfer
            ->setName('SprykerShop')
            ->setRootPath(APPLICATION_ROOT_DIR . 'vendor/spryker/spryker-shop/');

        $organizationCollection->addOrganization($organizationTransfer);

        $builder->add('organization', ChoiceType::class, [
            'choices' => $organizationCollection->getOrganizations(),
            'choice_label' => function (OrganizationTransfer $organizationTransfer) {
                return $organizationTransfer->getName();
            },
            'data_class' => OrganizationTransfer::class,
            'placeholder' => '',
        ]);
    }
}
