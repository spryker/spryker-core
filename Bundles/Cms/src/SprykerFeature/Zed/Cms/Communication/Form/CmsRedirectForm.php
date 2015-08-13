<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Cms\Communication\Form\Constraint\CmsConstraint;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsRedirectForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_REDIRECT = 'id_redirect';
    const FROM_URL = 'from_url';
    const TO_URL = 'to_url';

    /**
     * @var SpyUrlQuery
     */
    protected $urlByIdQuery;

    /**
     * @var UrlFacade
     */
    protected $urlFacade;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var CmsConstraint
     */
    protected $constraints;

    /**
     * @var string
     */
    protected $redirectUrl;

    /**
     * @param string $type
     */

    /**
     * @param SpyUrlQuery $urlByIdQuery
     * @param UrlFacade $urlFacade
     * @param CmsConstraint $constraints
     * @param string $formType
     */
    public function __construct(SpyUrlQuery $urlByIdQuery, UrlFacade $urlFacade, CmsConstraint $constraints, $formType)
    {
        $this->urlByIdQuery = $urlByIdQuery;
        $this->urlFacade = $urlFacade;
        $this->constraints = $constraints;
        $this->formType = $formType;
    }

    /**
     * @return CmsRedirectForm
     */
    protected function buildFormFields()
    {
        $urlConstraints = $this->constraints->getMandatoryConstraints();

        $urlConstraints[] = new Callback([
            'methods' => [
                function ($url, ExecutionContext $context) {
                    if ($this->urlFacade->hasUrl($url) && $this->redirectUrl !== $url) {
                        $context->addViolation('Url is already used');
                    }
                },
            ],
        ]);

        return $this->addHidden(self::ID_REDIRECT)
            ->addText(self::FROM_URL, [
                'label' => 'URL',
                'constraints' => $urlConstraints,
            ])
            ->addText(self::TO_URL, [
                'label' => 'To URL',
                'constraints' => $this->constraints->getMandatoryConstraints(),
            ])
            ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $url = $this->urlByIdQuery->findOne();

        if (isset($url)) {
            $this->redirectUrl = $url->getUrl();

            return [
                self::FROM_URL => $url->getUrl(),
                self::TO_URL => $url->getToUrl(),
            ];
        }
    }

}
