<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Orm\Zed\Cms\Persistence\SpyCmsBlockQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Shared\Transfer\TransferInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsBlockForm extends AbstractForm
{

    const TYPE_STATIC = 'static';
    const CATEGORY = 'category';
    const PRODUCT = 'product';
    const FIELD_SELECT_VALUE = 'selectValue';
    const FIELD_ID_CMS_BLOCK = 'idCmsBlock';
    const FIELD_FK_PAGE = 'fkPage';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_NAME = 'name';
    const FIELD_TYPE = 'type';
    const FIELD_VALUE = 'value';
    const FIELD_CURRENT_TEMPLATE = 'cur_temp';
    const PAGE = 'Page';
    const FIELD_IS_ACTIVE = 'is_active';

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
    protected $idCmsBlock;

    /**
     * @var string
     */
    protected $blockName;

    /**
     * @var string
     */
    protected $blockType;

    /**
     * @var string
     */
    protected $blockValue;

    /**
     * @var string
     */
    protected $selectValue;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsBlockQuery $blockPageByIdQuery
     * @param string $formType
     * @param int $idCmsBlock
     */
    public function __construct(
        SpyCmsTemplateQuery $templateQuery,
        SpyCmsBlockQuery $blockPageByIdQuery,
        $formType,
        $idCmsBlock
    ) {
        $this->templateQuery = $templateQuery;
        $this->blockPageByIdQuery = $blockPageByIdQuery;
        $this->formType = $formType;
        $this->idCmsBlock = $idCmsBlock;
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
        return 'cms_block';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $blockConstraints = $this->getConstraints()->getMandatoryConstraints();

        $blockConstraints[] = $this->getConstraints()->createConstraintCallback([
            'methods' => [
                function ($name, ExecutionContext $context) {
                    $formData = $context->getRoot()->getViewData();
                    if (!empty($this->checkExistingBlock($name, $formData)) && ($this->blockName !== $name
                        || $this->blockType !== $formData['type']
                        || $this->blockValue !== (int) $formData['value'])
                    ) {
                        $context->addViolation('Block name with same Type and Value already exists.');
                    }
                },
            ],
        ]);

        $builder->add(self::FIELD_ID_CMS_BLOCK, 'hidden')
            ->add(self::FIELD_CURRENT_TEMPLATE, 'hidden')
            ->add(self::FIELD_FK_PAGE, 'hidden')
            ->add(self::FIELD_FK_TEMPLATE, 'choice', [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->add(self::FIELD_NAME, 'text', [
                'label' => 'Name',
                'constraints' => $blockConstraints,
            ])
            ->add(self::FIELD_TYPE, 'choice', [
                'label' => 'Type',
                'choices' => [
                    self::TYPE_STATIC => 'Static',
                    self::CATEGORY => 'Category',
                    self::PRODUCT => 'Product',
                ],
            ])
            ->add(self::FIELD_SELECT_VALUE, 'text', [
                'label' => 'Value',
            ])
            ->add(self::FIELD_VALUE, 'hidden', [
                'label' => 'Value',
            ])
            ->add(self::FIELD_IS_ACTIVE, 'checkbox', [
                'label' => 'Active',
            ]);
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
    public function populateFormFields()
    {
        if ($this->idCmsBlock) {
            $pageUrlTemplate = $this->blockPageByIdQuery->findOne();
            $this->blockName = $pageUrlTemplate->getName();
            $this->blockType = $pageUrlTemplate->getType();
            $this->blockValue = $pageUrlTemplate->getValue();
            $this->selectValue = $pageUrlTemplate->getCategoryName();

            return [
                self::FIELD_ID_CMS_BLOCK => $pageUrlTemplate->getIdCmsBlock(),
                self::FIELD_FK_PAGE => $pageUrlTemplate->getFkPage(),
                self::FIELD_FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::FIELD_NAME => $pageUrlTemplate->getName(),
                self::FIELD_TYPE => $pageUrlTemplate->getType(),
                self::FIELD_SELECT_VALUE => $pageUrlTemplate->getCategoryName(),
                self::FIELD_VALUE => $pageUrlTemplate->getValue(),
                self::FIELD_CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
                self::FIELD_IS_ACTIVE => (bool) $pageUrlTemplate->getIsActive(),
            ];
        }
    }

    /**
     * @param string $name
     * @param array $formData
     *
     * @return array
     */
    private function checkExistingBlock($name, array $formData)
    {
        return $this->templateQuery->useSpyCmsPageQuery()
            ->useSpyCmsBlockQuery()
            ->filterByName($name)
            ->filterByType($formData['type'])
            ->filterByValue($formData['value'])
            ->find()
            ->getData();
    }

}
