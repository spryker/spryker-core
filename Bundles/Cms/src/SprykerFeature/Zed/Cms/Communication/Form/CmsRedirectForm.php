<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;


use SprykerFeature\Zed\Cms\CmsDependencyProvider;
use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use SprykerFeature\Zed\Url\Persistence\UrlQueryContainer;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Context\ExecutionContext;

class CmsRedirectForm extends AbstractForm
{

    const ADD = 'add';
    const UPDATE = 'update';
    const ID_REDIRECT = "id_redirect";
    const FROM_URL = 'from_url';
    const TO_URL = 'to_url';


    protected $urlQuery;

    protected $urlFacade;

    protected $type;

    /**
     * @param string $type
     */

    public function __construct($urlQuery, $type, $urlFacade)
    {
        $this->urlQuery = $urlQuery;
        $this->type = $type;
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

        if (self::ADD === $this->type) {
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

        if (self::UPDATE == $this->type) {
            $urlParams['disabled'] = 'disabled';
        }

        return $this->addHidden(self::ID_REDIRECT, [
            'label' => 'Redirect ID',
        ])
            ->addText(self::FROM_URL,$urlParams)
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
        $spyUrl = $this->urlQuery->findOne();

        if($spyUrl){
            return [
                self::FROM_URL => $spyUrl->getUrl(),
                self::TO_URL => $spyUrl->getToUrl(),
            ];
        }
    }

}
