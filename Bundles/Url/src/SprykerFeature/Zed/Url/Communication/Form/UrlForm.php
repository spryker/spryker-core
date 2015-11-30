<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Url\Communication\Form;

use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints;

class UrlForm extends AbstractForm
{

    protected function buildFormFields()
    {
        // @todo: Implement buildFormFields() method.
    }

    protected function populateFormFields()
    {
        // @todo: Implement populateFormFields() method.
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [
        ];
    }

    /**
     * @todo add constraints
     *
     * @return array
     */
    public function addFormFields()
    {
        $fields = [];
        $fields[] = $this->addField('url')
            ->setLabel('Url')
            ->setConstraints([
                new Constraints\Required([
                    new Constraints\Type([
                        'type' => 'string',
                    ]),
                    new Constraints\NotBlank(),
                ]),
            ]);
        $fields[] = $this->addField('fk_locale')
            ->setAccepts($this->getLocales());
        $fields[] = $this->addField('resource_type')
            ->setAccepts($this->getResourceTypes());
        $fields[] = $this->addField('resource')
            ->setRefresh(true)
            ->setAccepts($this->getResources());

        return $fields;
    }

    public function getResources()
    {
        $data = $this->getRequestData();

        if (isset($data['resource'])) {
            if ($data['resource'][0] === 'A') {
                return [
                    'value' => 3,
                    'label' => 'Alalal',
                ];
            }

            if ($data['resource'][0] === 'B') {
                return [
                    'value' => 3,
                    'label' => 'Bobobo',
                ];
            }
        }

        return [];
    }

    public function getResourceTypes()
    {
        return [
            [
                'value' => 'category',
                'label' => 'Category',
            ],
            [
                'value' => 'redirect',
                'label' => 'Redirect',
            ],
        ];
    }

    /**
     * @return array
     */
    public function getLocales()
    {
        $locales = $this->getLocaleQueryContainer()
            ->queryLocales()
            ->find()
            ->toArray();

        return $this->formatLocalesArray($locales);
    }

    /**
     * @return \SprykerEngine\Zed\Locale\Persistence\LocaleQueryContainer
     */
    public function getLocaleQueryContainer()
    {
        return $this->getLocator()->locale()->queryContainer();
    }

    /**
     * @param $locales
     *
     * @return array
     */
    protected function formatLocalesArray($locales)
    {
        foreach ($locales as &$item) {
            $item = [
                'value' => $item['IdLocale'],
                'label' => $item['LocaleName'],
            ];
        }

        return $locales;
    }

}
