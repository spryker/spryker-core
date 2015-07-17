<?php

namespace SprykerFeature\Zed\Shipment\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use SprykerFeature\Zed\Shipment\Persistence\Propel\SpyShipmentCarrierQuery;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class ShipmentCarrierForm extends AbstractForm
{
    /**
     * @var SpyShipmentCarrierQuery
     */
    protected $shipmentCarrierQuery;

    /**
     * @param $shipmentCarrierQuery
     */
    public function __construct(SpyShipmentCarrierQuery $shipmentCarrierQuery)
    {
        $this->shipmentCarrierQuery = $shipmentCarrierQuery;
    }

    /**
     * @return $this
     */
    protected function buildFormFields()
    {
        return $this->addText(
            'iso2_code',
            [
                'label' => 'ISO2 Code',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please provide correct ISO2 Code'
                    ]),
                    new Length([
                        'min' => 2,
                        'max' => 2
                    ])
                ]
            ]
        )
            ->addText('iso3_code',
                [
                    'label' => 'ISO3 Code',
                    'constraints' => [
                        new NotBlank(),
                        new Length([
                            'min' => 3,
                            'max' => 3
                        ])
                    ]
                ]
            )
            ->addText('name',
                [
                    'label' => 'Country Name',
                    'constraints' => [
                        new NotBlank(),
                    ]
                ]
            )
            ->addCheckbox('postal_code_mandatory',
                [
                    'label' => 'Is postal code mandatory',
                ]
            )
            ->addText('postal_code_regex',
                [
                    'label' => 'Postal code (regex)',
                ]
            )
            ->addHidden('id_country')
            ->addSubmit();

    }

    /**
     * @return array
     */
    protected function populateFormFields()
    {
        $result = [];

        $idCountry = $this->request->get('id_country');
        if (!is_null($idCountry)) {
            $countryDetailEntity = $this
                ->countryQuery
                ->findOneByIdCountry($idCountry);

            $result = $countryDetailEntity->toArray();
        }

        return $result;
    }
}
