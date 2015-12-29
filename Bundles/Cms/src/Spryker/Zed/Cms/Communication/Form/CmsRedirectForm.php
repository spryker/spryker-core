<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace Spryker\Zed\Cms\Communication\Form;

use Spryker\Shared\Gui\Form\AbstractForm;
use Spryker\Zed\Cms\Dependency\Facade\CmsToUrlInterface;
use Orm\Zed\Url\Persistence\SpyUrlQuery;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsRedirectForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const FIELD_ID_REDIRECT = 'id_redirect';
    const FIELD_FROM_URL = 'from_url';
    const FIELD_TO_URL = 'to_url';
    const FIELD_STATUS = 'status';

    /**
     * @var SpyUrlQuery
     */
    protected $urlByIdQuery;

    /**
     * @var CmsToUrlInterface
     */
    protected $urlFacade;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var string
     */
    protected $redirectUrl;

    /**
     * @param string $type
     */

    /**
     * @param SpyUrlQuery $urlByIdQuery
     * @param CmsToUrlInterface $urlFacade
     * @param string $formType
     */
    public function __construct(SpyUrlQuery $urlByIdQuery, CmsToUrlInterface $urlFacade, $formType)
    {
        $this->urlByIdQuery = $urlByIdQuery;
        $this->urlFacade = $urlFacade;
        $this->formType = $formType;
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
        return 'cms_redirect';
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
                    if ($this->urlFacade->hasUrl($url) && $this->redirectUrl !== $url) {
                        $context->addViolation('Url is already used');
                    }
                },
            ],
        ]);

        $builder->add(self::FIELD_ID_REDIRECT, 'hidden')
            ->add(self::FIELD_FROM_URL, 'text', [
                'label' => 'URL',
                'constraints' => $urlConstraints,
            ])
            ->add(self::FIELD_TO_URL, 'text', [
                'label' => 'To URL',
                'constraints' => $this->getConstraints()->getMandatoryConstraints(),
            ])
            ->add(self::FIELD_STATUS, 'text');
    }

    /**
     * @return array
     */
    public function populateFormFields()
    {
        $url = $this->urlByIdQuery->findOne();

        if (!isset($url)) {
            return [];
        }

        $this->redirectUrl = $url->getUrl();

        return [
            self::FIELD_FROM_URL => $url->getUrl(),
            self::FIELD_TO_URL => $url->getToUrl(),
            self::FIELD_STATUS => $url->getStatus(),
        ];
    }

}
