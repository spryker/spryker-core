<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ShipmentGui\Communication\Form;

use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\ShipmentGui\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\ShipmentGui\Communication\SalesCommunicationFactory getFactory()
 * @method \Spryker\Zed\ShipmentGui\Persistence\SalesQueryContainerInterface getQueryContainer()
 * @method \Spryker\Zed\ShipmentGui\SalesConfig getConfig()
 * @method \Spryker\Zed\ShipmentGui\Persistence\SalesRepositoryInterface getRepository()
 */
class CustomerForm extends AbstractType
{
    public const OPTION_SALUTATION_CHOICES = 'salutation_choices';
}
