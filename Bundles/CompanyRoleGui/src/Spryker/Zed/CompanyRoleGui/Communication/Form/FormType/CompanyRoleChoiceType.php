<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyRoleGui\Communication\Form\FormType;

use Generated\Shared\Transfer\CompanyRoleCollectionTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Spryker\Zed\CompanyRoleGui\CompanyRoleGuiConfig getConfig()
 * @method \Spryker\Zed\CompanyRoleGui\Communication\CompanyRoleGuiCommunicationFactory getFactory()
 */
class CompanyRoleChoiceType extends ChoiceType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $callbackTransformer = new CallbackTransformer(
            [$this, 'roleCollectionInputDataTransformer'],
            [$this, 'getOutputDataCallbackRoleCollectionTransformer'],
        );

        $builder->addModelTransformer($callbackTransformer);
    }

    /**
     * @param array $roleCollection
     *
     * @return array
     */
    public function roleCollectionInputDataTransformer($roleCollection = []): array
    {
        $roles = [];

        if (isset($roleCollection[CompanyRoleCollectionTransfer::ROLES])) {
            foreach ($roleCollection[CompanyRoleCollectionTransfer::ROLES] as $role) {
                $roles[] = $role[CompanyRoleTransfer::ID_COMPANY_ROLE];
            }
        }

        return $roles;
    }

    /**
     * @param array $roleCollectionSubmitted
     *
     * @return \Generated\Shared\Transfer\CompanyRoleCollectionTransfer
     */
    public function getOutputDataCallbackRoleCollectionTransformer($roleCollectionSubmitted = []): CompanyRoleCollectionTransfer
    {
        $companyRoleCollectionTransfer = new CompanyRoleCollectionTransfer();

        foreach ($roleCollectionSubmitted as $role) {
            $companyRoleTransfer = (new CompanyRoleTransfer())
                ->setIdCompanyRole($role);

            $companyRoleCollectionTransfer->addRole($companyRoleTransfer);
        }

        return $companyRoleCollectionTransfer;
    }
}
