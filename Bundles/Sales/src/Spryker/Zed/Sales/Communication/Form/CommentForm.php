<?php

namespace Spryker\Zed\Sales\Communication\Form;

use Generated\Shared\Transfer\CommentTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentForm extends AbstractType
{

    const FORM_NAME = 'comment';

    /**
     * @return string
     */
    public function getName()
    {
        return self::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCommentField($builder)
            ->addFkSalesOrderField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCommentField(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::MESSAGE, 'textarea', [
            'constraints' => [
                new NotBlank(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkSalesOrderField(FormBuilderInterface $builder)
    {
        $builder->add(CommentTransfer::FK_SALES_ORDER, 'hidden');

        return $this;
    }

}
