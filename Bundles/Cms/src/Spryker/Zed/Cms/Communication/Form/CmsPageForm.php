<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Shared\Transfer\TransferInterface;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Spryker\Zed\Cms\Persistence\CmsQueryContainer;
use Orm\Zed\Cms\Persistence\SpyCmsPageQuery;
use Orm\Zed\Cms\Persistence\SpyCmsTemplateQuery;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsPageForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const FIELD_ID_CMS_PAGE = 'idCmsPage';
    const FIELD_FK_TEMPLATE = 'fkTemplate';
    const FIELD_URL = 'url';
    const FIELD_CURRENT_TEMPLATE = 'cur_temp';
    const PAGE = 'Page';
    const FIELD_IS_ACTIVE = 'is_active';

    /**
     * @var SpyCmsTemplateQuery
     */
    protected $templateQuery;

    /**
     * @var SpyCmsPageQuery
     */
    protected $pageUrlByIdQuery;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var int
     */
    protected $idPage;

    /**
     * @var CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var string
     */
    protected $pageUrl;

    /**
     * @param SpyCmsTemplateQuery $templateQuery
     * @param SpyCmsPageQuery $pageUrlByIdQuery
     * @param CmsToUrlInterface $urlFacade
     * @param string $formType
     * @param int $idPage
     */
    public function __construct(SpyCmsTemplateQuery $templateQuery, SpyCmsPageQuery $pageUrlByIdQuery, CmsToUrlInterface $urlFacade, $formType, $idPage)
    {
        $this->templateQuery = $templateQuery;
        $this->pageUrlByIdQuery = $pageUrlByIdQuery;
        $this->formType = $formType;
        $this->idPage = $idPage;
        $this->urlFacade = $urlFacade;
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
        return 'cms_page';
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $urlConstraints = $this->getConstraints()->getMandatoryConstraints();

        $urlConstraints[] = $this->getConstraints()->createConstraintCallback([
            'methods' => [
                function ($url, ExecutionContext $context) {
                    if ($this->urlFacade->hasUrl($url) && $this->pageUrl !== $url) {
                        $context->addViolation('Url is already used');
                    }
                },
            ],
        ]);

        $builder->add(self::FIELD_ID_CMS_PAGE, 'hidden')
            ->add(CmsQueryContainer::ID_URL, 'hidden')
            ->add(self::FIELD_CURRENT_TEMPLATE, 'hidden')
            ->add(self::FIELD_FK_TEMPLATE, 'choice', [
                'label' => 'Template',
                'choices' => $this->getTemplateList(),
            ])
            ->add(self::FIELD_URL, 'text', [
                'label' => 'URL',
                'constraints' => $urlConstraints,
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
        if ($this->idPage === null) {
            return null;
        }

        $pageUrlTemplate = $this->pageUrlByIdQuery->findOne();

        $this->pageUrl = $pageUrlTemplate->getUrl();

        return [
            self::FIELD_ID_CMS_PAGE => $pageUrlTemplate->getIdCmsPage(),
            self::FIELD_FK_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
            self::FIELD_URL => $pageUrlTemplate->getUrl(),
            self::FIELD_CURRENT_TEMPLATE => $pageUrlTemplate->getFkTemplate(),
            self::FIELD_IS_ACTIVE => $pageUrlTemplate->getIsActive(),
            CmsQueryContainer::FIELD_ID_URL => $pageUrlTemplate->getIdUrl(),
        ];
    }

}
