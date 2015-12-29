<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Zed\Cms\Business\CmsFacade;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsGlossaryForm extends AbstractForm
{

    const FIELD_FK_PAGE = 'fkPage';
    const FIELD_PLACEHOLDER = 'placeholder';
    const FIELD_GLOSSARY_KEY = 'glossary_key';
    const FIELD_ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';
    const AUTO_GLOSSARY = 'Auto';
    const FIELD_SEARCH_OPTION = 'search_option';
    const GLOSSARY_NEW = 'New glossary';
    const GLOSSARY_FIND = 'Find glossary';
    const FULLTEXT_SEARCH = 'Full text';
    const FIELD_TRANSLATION = 'translation';
    const FIELD_TEMPLATE_NAME = 'templateName';

    /**
     * @var SpyCmsGlossaryKeyMappingQuery
     */
    protected $glossaryByIdQuery;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var int
     */
    protected $idMapping;

    /**
     * @var array
     */
    protected $placeholder;

    /**
     * @var CmsFacade
     */
    protected $cmsFacade;

    /**
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryByIdQuery
     * @param CmsFacade $cmsFacade
     * @param int $idPage
     * @param int $idMapping
     * @param array $placeholder
     */
    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryByIdQuery, CmsFacade $cmsFacade, $idPage, $idMapping, $placeholder)
    {
        $this->glossaryByIdQuery = $glossaryByIdQuery;
        $this->cmsFacade = $cmsFacade;
        $this->idPage = $idPage;
        $this->idMapping = $idMapping;
        $this->placeholder = $placeholder;
    }

    /**
     * @return null
     */
    protected function getDataClass()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'cms_glossary';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $placeholderConstraints = $this->getConstraints()->getMandatoryConstraints();

        if (!isset($this->idMapping)) {
            $placeholderConstraints[] = $this->getConstraints()->createConstraintCallback([
                'methods' => [
                    function ($placeholder, ExecutionContext $context) {
                        if ($this->cmsFacade->hasPagePlaceholderMapping($this->idPage, $placeholder)) {
                            $context->addViolation('Placeholder has already mapped');
                        }
                    },
                ],
            ]);
        }

        $placeholderParams = [
            'label' => 'Placeholder',
            'constraints' => $placeholderConstraints,
        ];

        $placeholderParams['disabled'] = 'disabled';

        $builder->add(self::FIELD_FK_PAGE, 'hidden')
            ->add(self::FIELD_ID_KEY_MAPPING, 'hidden')
            ->add(self::FIELD_TEMPLATE_NAME, 'hidden')
            ->add(self::FIELD_PLACEHOLDER, 'text', $placeholderParams)
            ->add(self::FIELD_SEARCH_OPTION, 'choice', [
                'label' => 'Search Type',
                'choices' => [
                    self::AUTO_GLOSSARY,
                    self::GLOSSARY_NEW,
                    self::GLOSSARY_FIND,
                    self::FULLTEXT_SEARCH,
                ],
            ])
            ->add(self::FIELD_GLOSSARY_KEY, 'text')
            ->add(self::FIELD_TRANSLATION, 'textarea', [
                'label' => 'Content',
                'constraints' => $this->getConstraints()->getRequiredConstraints(),
                'attr' => [
                    'class' => 'html-editor',
                ],
            ]);
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $formItems = [
            self::FIELD_FK_PAGE => $this->idPage,
            self::FIELD_ID_KEY_MAPPING => $this->idMapping,
        ];

        if ($this->placeholder) {
            $formItems[self::FIELD_PLACEHOLDER] = $this->placeholder;
        }

        if ($this->idMapping !== null) {
            $glossaryMapping = $this->glossaryByIdQuery->findOne();

            if ($glossaryMapping) {
                $formItems[self::FIELD_PLACEHOLDER] = $glossaryMapping->getPlaceholder();
                $formItems[self::FIELD_GLOSSARY_KEY] = $glossaryMapping->getKeyname();
                $formItems[self::FIELD_TRANSLATION] = $glossaryMapping->getTrans();
            }
        }

        return $formItems;
    }

}
