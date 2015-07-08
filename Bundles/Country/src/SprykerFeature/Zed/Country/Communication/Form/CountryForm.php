<?php

namespace SprykerFeature\Zed\Country\Communication\Form;

use SprykerEngine\Zed\Gui\Business\AbstractFormManager;
use SprykerEngine\Zed\Gui\Business\ConstraintBuilder;

use SprykerFeature\Zed\Library\Propel\Builder\QueryBuilder;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method CountryQueryContainer getQueryContainer()
 */
class CountryForm extends AbstractFormManager
{
    protected function buildForm($data)
    {
        return $this->addText(
            'iso2_code',
            [
                'label'=>'ISO2 Code',
                'constraints' => ConstraintBuilder::getInstance()
                    ->addNotBlank()
                    ->addLength([
                        'min' => 2,
                        'max' => 2
                    ])
                    ->getConstraints()
            ]
        )
            ->addText('iso3_code',
                [
                    'label'=>'ISO3 Code',
                    'constraints' => ConstraintBuilder::getInstance()
                        ->addNotBlank()
                        ->addLength([
                            'min' => 3,
                            'max' => 3
                        ])
                        ->getConstraints()
                ])
            ->addText('name',
                [
                    'label'=>'Country Name',
                    'constraints' => ConstraintBuilder::getInstance()
                        ->addNotBlank()
                        ->getConstraints()
                ])
            ->addText('postal_code_mandatory',
                [
                    'label'=>'Postal Code',
                    'constraints' => ConstraintBuilder::getInstance()
                        ->addNotBlank()
                        ->getConstraints()
                ])
            ->addText('postal_code_regex', ['label'=>'Postal code (regex)'])
            ->addHidden('id_country')
            ->addSubmit()
            ->setData($data);
    }

    public function createCountry()
    {
        $data = [];

        return $this->buildForm($data);
    }

    public function process(Request $request, &$errors)
    {
        $countryId = $request->get('id_country', false);

        if ($request->isMethod('POST')) {
            if(false === $data = $this->processRequest($request)){
                $errors = $this->getErrors();
            }
        }

        return !empty($countryId) ?
            $this->updateCountry($countryId) :
            $this->createCountry();
    }

    public function updateCountry($countryId)
    {

        $countryDetailsEntity = $this->getQueryContainer()->queryCountries()->findOneByIdCountry($countryId);
        $countryDetails = $countryDetailsEntity->toArray();

        return $this->buildForm($countryDetails);
    }
}
