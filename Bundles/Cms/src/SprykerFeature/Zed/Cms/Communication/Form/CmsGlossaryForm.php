<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Business\CmsFacade;
use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use Orm\Zed\Cms\Persistence\SpyCmsGlossaryKeyMappingQuery;
use SprykerFeature\Zed\Glossary\Business\GlossaryFacade;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsGlossaryForm extends AbstractForm
{

    const FK_PAGE = 'fkPage';
    const PLACEHOLDER = 'placeholder';
    const GLOSSARY_KEY = 'glossary_key';
    const ID_KEY_MAPPING = 'idCmsGlossaryKeyMapping';
    const AUTO_GLOSSARY = 'Auto';
    const SEARCH_OPTION = 'search_option';
    const GLOSSARY_NEW = 'New glossary';
    const GLOSSARY_FIND = 'Find glossary';
    const FULLTEXT_SEARCH = 'Full text';
    const TRANSLATION = 'translation';
    const TEMPLATE_NAME = 'templateName';

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
     * @var CmsConstraint
     */
    protected $constraints;

    /**
     * @param SpyCmsGlossaryKeyMappingQuery $glossaryByIdQuery
     * @param CmsFacade $cmsFacade
     * @param CmsConstraint $constraints
     * @param int $idPage
     * @param int $idMapping
     * @param array $placeholder
     */

    public function __construct(SpyCmsGlossaryKeyMappingQuery $glossaryByIdQuery, CmsFacade $cmsFacade, CmsConstraint $constraints, $idPage, $idMapping, $placeholder)
    {
        $this->glossaryByIdQuery = $glossaryByIdQuery;
        $this->cmsFacade = $cmsFacade;
        $this->constraints = $constraints;
        $this->idPage = $idPage;
        $this->idMapping = $idMapping;
        $this->placeholder = $placeholder;
    }

    /**
     * @return CmsRedirectForm
     */
    protected function buildFormFields()
    {
        $placeholderConstraints = $this->constraints->getMandatoryConstraints();

        if (!isset($this->idMapping)) {
            $placeholderConstraints[] = new Callback([
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

        return $this->addHidden(self::FK_PAGE)
            ->addHidden(self::ID_KEY_MAPPING)
            ->addHidden(self::TEMPLATE_NAME)
            ->addText(self::PLACEHOLDER, $placeholderParams)
            ->addChoice(self::SEARCH_OPTION, [
                'label' => 'Search Type',
                'choices' => [
                    self::AUTO_GLOSSARY,
                    self::GLOSSARY_NEW,
                    self::GLOSSARY_FIND,
                    self::FULLTEXT_SEARCH,
                ],
            ])
            ->addText(self::GLOSSARY_KEY)
            ->addTextarea(self::TRANSLATION,[
                'label' => 'Content',
                'constraints' => $this->constraints->getRequiredConstraints(),
            ])
            ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $formItems = [
            self::FK_PAGE => $this->idPage,
            self::ID_KEY_MAPPING => $this->idMapping,
        ];

        if ($this->placeholder) {
            $formItems[self::PLACEHOLDER] = $this->placeholder;
        }

        if (null !== $this->idMapping) {
            $glossaryMapping = $this->glossaryByIdQuery->findOne();

            if ($glossaryMapping) {
                $formItems[self::PLACEHOLDER] = $glossaryMapping->getPlaceholder();
                $formItems[self::GLOSSARY_KEY] = $glossaryMapping->getKeyname();
                $formItems[self::TRANSLATION] = $glossaryMapping->getTrans();
            }
        }

        return $formItems;
    }
}
