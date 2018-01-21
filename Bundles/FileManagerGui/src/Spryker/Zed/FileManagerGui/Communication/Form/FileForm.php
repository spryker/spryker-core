<?php

namespace Spryker\Zed\FileManagerGui\Communication\Form;

use Spryker\Zed\CmsGui\Communication\Form\ArrayObjectTransformerTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FileForm extends AbstractType
{

    use ArrayObjectTransformerTrait;

    const FIELD_FILE_NAME = 'fileName';
    const FIELD_FILE_CONTENT = 'fileContent';
    const FIELD_FILE_ATTRIBUTES = 'fileAttributes';
    const OPTION_DATA_CLASS_ATTRIBUTES = 'data_class_attributes';

    /**
     * @var FileAttributesFormType
     */
    protected $attributesFormType;

    /**
     * FileForm constructor.
     * @param FileAttributesFormType $attributesFormType
     */
    public function __construct(FileAttributesFormType $attributesFormType)
    {
        $this->attributesFormType = $attributesFormType;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'file';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
//        $resolver->setRequired(static::OPTION_TEMPLATE_CHOICES);
//        $resolver->setRequired(static::OPTION_LOCALES_CHOICES);

//        $resolver->setDefaults([
//            'validation_groups' => function (FormInterface $form) {
//                $defaultData = $form->getConfig()->getData();
//                if (array_key_exists(static::FIELD_URL, $defaultData) === false ||
//                    $defaultData[static::FIELD_URL] !== $form->getData()[static::FIELD_URL]
//                ) {
//                    return [Constraint::DEFAULT_GROUP, static::GROUP_UNIQUE_URL_CHECK];
//                }
//                return [Constraint::DEFAULT_GROUP];
//            },
//        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addFileNameField($builder)
            ->addFileAttributesFormCollection($builder, $options)
            ->addFileContentField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FILE_NAME, TextType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFileContentField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_FILE_CONTENT, FileType::class);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFileAttributesFormCollection(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FILE_ATTRIBUTES, CollectionType::class, [
            'type' => $this->attributesFormType,
            'allow_add' => true,
            'allow_delete' => true,
            'entry_options' => [
//                'data_class' => $options[static::OPTION_DATA_CLASS_ATTRIBUTES],
//                FileAttributesFormType::OPTION_AVAILABLE_LOCALES => $options[FileAttributesFormType::OPTION_AVAILABLE_LOCALES],
            ],
        ]);

//        $builder->get(static::FIELD_FILE_ATTRIBUTES)
//            ->addModelTransformer($this->createArrayObjectModelTransformer());

        return $this;
    }

}
