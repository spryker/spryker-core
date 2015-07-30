<?php

namespace SprykerFeature\Zed\Cms\Communication\Form;


use SprykerFeature\Zed\Cms\Persistence\Propel\SpyCmsTemplateQuery;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Sales\Persistence\Propel\Base\SpySalesOrderQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\Map\SpyCustomerTableMap;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;

class CmsRedirectForm extends AbstractForm
{

    const ID_REDIRECT = "idRedirect";
    const FROM_URL = 'from_url';
    const TO_URL = 'to_url';

    protected $type;

    /**
     * @param string $type
     */

    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return CmsRedirectForm
     */
    protected function buildFormFields()
    {
        return $this->addHidden(self::ID_REDIRECT, [
            'label' => 'Redirect ID',
        ])
            ->addText(self::FROM_URL, [
            'label' => 'From URL',
            'constraints' => [
                new Required(),
                new NotBlank(),
                new Length(['max' => 256]),
            ],
        ])
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
//        $order = $this->orderQuery->findOne();
//
//        return [
//            self::FIRST_NAME => $order->getFirstName(),
//            self::LAST_NAME => $order->getLastName(),
//            self::SALUTATION => $order->getSalutation(),
//            self::EMAIL => $order->getEmail(),
//        ];
    }

}
