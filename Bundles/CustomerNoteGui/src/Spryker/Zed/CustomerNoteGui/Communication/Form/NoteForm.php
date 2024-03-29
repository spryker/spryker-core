<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerNoteGui\Communication\Form;

use Generated\Shared\Transfer\SpyCustomerNoteEntityTransfer;
use Spryker\Zed\Kernel\Communication\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \Spryker\Zed\Sales\Business\SalesFacadeInterface getFacade()
 * @method \Spryker\Zed\CustomerNoteGui\Communication\CustomerNoteGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\Sales\Persistence\SalesQueryContainerInterface getQueryContainer()
 */
class NoteForm extends AbstractType
{
    /**
     * @var string
     */
    protected const FORM_NAME = 'note';

    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE = 'Please add your message to post a comment';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addNoteField($builder)
            ->addFkCustomerField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addNoteField(FormBuilderInterface $builder)
    {
        $builder->add(SpyCustomerNoteEntityTransfer::MESSAGE, TextareaType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => static::VALIDATION_MESSAGE,
                ]),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCustomerField(FormBuilderInterface $builder)
    {
        $builder->add(SpyCustomerNoteEntityTransfer::FK_CUSTOMER, HiddenType::class);

        return $this;
    }
}
