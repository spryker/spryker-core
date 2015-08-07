<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Url\Business\UrlFacade;
use SprykerFeature\Zed\Url\Persistence\Propel\SpyUrlQuery;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
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
     * @param string $type
     */

    /**
     * @param SpyUrlQuery $urlByIdQuery
     * @param string $formType
     * @param UrlFacade $urlFacade
     */
    public function __construct(SpyUrlQuery $urlByIdQuery, $formType, UrlFacade $urlFacade)
    {
        $this->urlByIdQuery = $urlByIdQuery;
        $this->formType = $formType;
        $this->urlFacade = $urlFacade;
    }

    /**
     * @return CmsRedirectForm
     */
    protected function buildFormFields()
    {

        $urlConstraints = [
            new Required(),
            new NotBlank(),
            new Length(['max' => 256]),
        ];

        if (self::ADD === $this->formType) {
            $urlConstraints[] = new Callback([
                'methods' => [
                    function ($url, ExecutionContext $context) {
                        if ($this->urlFacade->hasUrl($url)) {
                            $context->addViolation('Url is already used');
                        }
                    },
                ],
            ]);
        }

        $urlParams = [
            'label' => 'URL',
            'constraints' => $urlConstraints,
        ];

        if (self::UPDATE === $this->formType) {
            $urlParams['disabled'] = 'disabled';
        }

        return $this->addHidden(self::ID_REDIRECT)
            ->addText(self::FROM_URL, $urlParams)
            ->addText(self::TO_URL, [
                'label' => 'To URL',
                'constraints' => [
                    new Required(),
                    new NotBlank(),
                    new Length(['max' => 256]),
                ],
            ])
            ;
    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $url = $this->urlByIdQuery->findOne();

        if ($url) {
            return [
                self::FROM_URL => $url->getUrl(),
                self::TO_URL => $url->getToUrl(),
            ];
        }
    }

}
