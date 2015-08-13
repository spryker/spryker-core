<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\SearchPage\Communication\Form;

use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;
use SprykerFeature\Zed\SearchPage\Persistence\SearchPageQueryContainer;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints;

/**
 * @property SearchPageQueryContainer $queryContainer
 */
class PageElementForm extends AbstractForm
{

    const ID_SEARCH_PAGE_ELEMENT = 'id_search_page_element';
    const IS_ELEMENT_ACTIVE = 'is_element_active';
    const FK_SEARCH_DOCUMENT_ATTRIBUTE = 'fk_search_document_attribute';
    const FK_SEARCH_PAGE_ELEMENT_TEMPLATE = 'fk_search_page_element_template';
    const ELEMENT_KEY = 'element_key';

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [
            self::IS_ELEMENT_ACTIVE => false,
        ];
    }

    public function addFormFields()
    {
        $this->addField(self::ID_SEARCH_PAGE_ELEMENT);
        $this->addField(self::ELEMENT_KEY)
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
                new Constraints\NotBlank(),
            ])
        ;
        $this->addField(self::IS_ELEMENT_ACTIVE)
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'bool',
                ]),
            ])
        ;
        $this->addField(self::FK_SEARCH_DOCUMENT_ATTRIBUTE)
            ->setAccepts($this->getDocumentAttributes())
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer',
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getDocumentAttributes(), 'value'),
                    'message' => 'Please choose one of the given Attributes',
                ]),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            })
        ;
        $this->addField(self::FK_SEARCH_PAGE_ELEMENT_TEMPLATE)
            ->setAccepts($this->getTemplates())
            ->setRefresh(false)
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer',
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getTemplates(), 'value'),
                    'message' => 'Please choose one of the given Templates',
                ]),
                new Constraints\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            })
        ;
    }

    /**
     * @return array
     */
    protected function getDocumentAttributes()
    {
        $attributes = $this->queryContainer
            ->queryDocumentAttributeNames()
            ->setFormatter(new PropelArraySetFormatter())
            ->find()
        ;

        return $this->formatOptions($attributes, 'id', 'name');
    }

    /**
     * @return array
     */
    protected function getTemplates()
    {
        $templates = $this->queryContainer
            ->queryPageElementTemplateNames()
            ->setFormatter(new PropelArraySetFormatter())
            ->find()
        ;

        return $this->formatOptions($templates, 'id', 'name');
    }

    /**
     * @param array $options
     * @param mixed $valueKey
     * @param mixed $labelKey
     *
     * @return array
     */
    protected function formatOptions(array $options, $valueKey, $labelKey)
    {
        $formattedOptions = [];
        foreach ($options as $option) {
            $formattedOptions[] = $this->formatOption(
                (int) $option[$valueKey],
                $option[$labelKey]
            );
        }

        if (empty($formattedOptions)) {
            $formattedOptions[] = $this->formatOption('', '');
        }

        return $formattedOptions;
    }

    /**
     * @param string $option
     * @param string $label
     *
     * @return array
     */
    protected function formatOption($option, $label)
    {
        return [
            'value' => $option,
            'label' => $label,
        ];
    }

}
