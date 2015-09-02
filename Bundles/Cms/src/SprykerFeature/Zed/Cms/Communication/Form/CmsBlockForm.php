<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsBlockQuery;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsBlockForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_CMS_PAGE = 'idCmsPage';
    const FK_TEMPLATE = 'fkTemplate';
    const BLOCK = 'block';
    const CURRENT_TEMPLATE = 'cur_temp';
    const PAGE = 'Page';
    const IS_ACTIVE = 'is_active';

    /**
     * @var SpyCmsTemplateQuery
     */
    protected $templateQuery;

    /**
     * @var SpyCmsBlockQuery
     */
    protected $blockPageByIdQuery;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var CmsConstraint
     */
    protected $constraints;

    /**
     * @var string
     */
    protected $blockName;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsBlockQuery $blockPageByIdQuery
     * @param CmsConstraint $constraints
     * @param string $formType
     * @param int $idPage
     */
    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsBlockQuery $blockPageByIdQuery, CmsConstraint $constraints, $formType, $idPage)
    {
        $this->templateQuery = $templateQuery;
        $this->blockPageByIdQuery = $blockPageByIdQuery;
        $this->constraints = $constraints;
        $this->formType = $formType;
        $this->idPage = $idPage;
    }

    /**
     * @return CmsPageForm
     */
    protected function buildFormFields()
    {
        $blockConstraints = $this->constraints->getMandatoryConstraints();


            $blockConstraints[] = new Callback([
                'methods' => [
                    function ($name, ExecutionContext $context) {
                        if (!empty($this->templateQuery->useSpyCmsPageQuery()
                            ->useSpyCmsBlockQuery()
                            ->findByName($name)
                            ->getData() && $this->blockName !== $name)
                        ) {
                            $context->addViolation('Block name already exists.');
                        }
                    },
                ],
            ]);

        return $this->addHidden(self::ID_CMS_PAGE)
            ->addHidden(self::CURRENT_TEMPLATE)
            ->addChoice(self::FK_TEMPLATE, [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->addText(self::BLOCK, [
                'label' => 'Block Name',
                'constraints' => $blockConstraints,
            ])
            ->addCheckbox(self::IS_ACTIVE, [
                'label' => 'Active',
            ])
            ;
    }

    /**
     * @return array
     */
    protected function getTemplateList()
    {
        $templates = $this->templateQuery->find();

        $result = [];
        foreach ($templates->getData() as $template) {
            $result[$template->getIdCmsTemplate()] = $template->getTemplateName();
        }

        return $result;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        if ($this->idPage) {
            $pageUrlTemplate = $this->blockPageByIdQuery->findOne();
            $this->blockName = $pageUrlTemplate->getName();

            return [
                self::ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
                self::FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::BLOCK => $pageUrlTemplate->getName(),
                self::CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::IS_ACTIVE => (bool)$pageUrlTemplate->getIsActive(),
            ];
        }
    }
}
