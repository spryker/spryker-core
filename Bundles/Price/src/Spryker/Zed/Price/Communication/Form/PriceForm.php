<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Price\Communication\Form;

use Spryker\Zed\Gui\Communication\Form\AbstractForm;
use Propel\Runtime\Exception\PropelException;
use Symfony\Component\Validator\Constraints;
use Spryker\Zed\Price\Persistence\PriceQueryContainer;

class PriceForm extends AbstractForm
{

    /**
     * @var PriceQueryContainer
     */
    protected $queryContainer;

    /**
     * @return void
     */
    protected function buildFormFields()
    {
        // @todo: Implement buildFormFields() method.
    }

    /**
     * @return void
     */
    protected function populateFormFields()
    {
        // @todo: Implement populateFormFields() method.
    }

    /**
     * @return void
     */
    public function addFormFields()
    {
        $this->addField('id_price_product')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer',
                ]),
                new Constraints\NotBlank(),
            ]);
        $this->addField('sku_product')
            ->setAccepts(array_merge($this->getSkuAbstractProduct(), $this->getSkuConcreteProduct()))
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getAllProducts(), 'value'),
                    'message' => 'Please choose one of the given Sku',
                ]),
                new Constraints\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (string) $value : null;
            });
        $this->addField('price')
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'integer',
                ]),
                new Constraints\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField('price_type_name')
            ->setAccepts($this->getPriceType())
            ->setConstraints([
                new Constraints\Type([
                    'type' => 'string',
                ]),
                new Constraints\Choice([
                    'choices' => array_column($this->getPriceType(), 'value'),
                    'message' => 'Please choose one of the given Price Types',
                ]),
                new Constraints\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (string) $value : null;
            });
    }

    /**
     * @return array
     */
    public function getDefaultData()
    {
        return [
            'sku_product' => $this->stateContainer->getRequestValue('id_price_product'),
        ];
    }

    /**
     * @throws PropelException
     *
     * @return array
     */
    protected function getSkuConcreteProduct()
    {
        return $this->locator->product()->queryContainer()->queryConcreteSkuForm()
            ->find()
            ->toArray();
    }

    /**
     * @throws PropelException
     *
     * @return array
     */
    protected function getSkuAbstractProduct()
    {
        $query = $this->locator->product()->queryContainer()->queryAbstractSkuForm()
            ->find()
            ->toArray();

        foreach ($query as $index => $value) {
            $query[$index]['label'] = $value['label'] . ' - abstr.';
        }

        return $query;
    }

    /**
     * @throws PropelException
     *
     * @return array
     */
    protected function getPriceType()
    {
        return $this->queryContainer->queryPriceTypeForm()
            ->find()
            ->toArray();
    }

    /**
     * @return array
     */
    protected function getAllProducts()
    {
        return array_merge($this->getSkuAbstractProduct(), $this->getSkuConcreteProduct());
    }

}
