<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Stock\Communication\Form;

use SprykerFeature\Zed\Stock\Persistence\StockQueryContainer;
use SprykerFeature\Zed\Gui\Communication\Form\AbstractForm;
use Symfony\Component\Validator\Constraints as Assert;

class StockProductForm extends AbstractForm
{

    const ID_STOCK_PRODUCT = 'id_stock_product';
    const FK_PRODUCT = 'fk_product';
    const FK_STOCK = 'fk_stock';
    const QUANTITY = 'quantity';
    const IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';
    const SKU = 'sku';

    /**
     * @var StockQueryContainer
     */
    protected $queryContainer;

    protected function buildFormFields()
    {
        // @todo: Implement buildFormFields() method.
    }

    protected function populateFormFields()
    {
        // @todo: Implement populateFormFields() method.
    }

    public function addFormFields()
    {
        $this->addField(self::ID_STOCK_PRODUCT)
            ->setValue($this->getIdStockProduct())
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
            ]);

        $this->addField(self::FK_PRODUCT)
            ->setAccepts([[
                'value' => $this->getFkProduct(),
                'label' => $this->getSku(),
            ]])
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\Choice([
                    'choices' => [$this->getSku()],
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField(self::FK_STOCK)
            ->setAccepts($this->getStockTypes())
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getStockTypes(), 'value'),
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField(self::QUANTITY)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField(self::IS_NEVER_OUT_OF_STOCK)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'boolean',
                ]),
                new Assert\NotNull(),
            ])
            ->setValueHook(function ($value) {
                return $value === 1 ? true : false;
            });
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $idStockProduct = $this->getIdStockProduct();
        if ($idStockProduct !== null) {
            $stockProduct = $this->queryContainer
                ->queryStockProductByIdStockProduct((int) $idStockProduct)
                ->findOne()
            ;

            return [
                self::ID_STOCK_PRODUCT => $stockProduct->getIdStockProduct(),
                self::FK_PRODUCT => $stockProduct->getFkProduct(),
                self::FK_STOCK => $stockProduct->getFkStock(),
                self::QUANTITY => $stockProduct->getQuantity(),
                self::IS_NEVER_OUT_OF_STOCK => $stockProduct->getIsNeverOutOfStock(),
            ];
        }

        return [];
    }

    /**
     * @return array
     */
    protected function getStockTypes()
    {
        $stockTypes = $this->queryContainer
            ->queryAllStockTypes()
            ->find()
        ;

        $stockTypeOptions = [];
        foreach ($stockTypes as $stockType) {
            $stockTypeOptions[] = $this->formatFieldOption($stockType->getIdStock(), $stockType->getName());
        }

        return $stockTypeOptions;
    }

    /**
     * @return string
     */
    protected function getSku()
    {
        return $this->stateContainer->getRequestValue(self::SKU);
    }

    /**
     * @return int
     */
    protected function getFkProduct()
    {
        return $this->stateContainer->getRequestValue(self::FK_PRODUCT);
    }

    /**
     * @return int
     */
    protected function getCurrentStockType()
    {
        return $this->stateContainer->getRequestValue(self::FK_STOCK);
    }

    /**
     * @return int
     */
    protected function getIdStockProduct()
    {
        return $this->stateContainer->getRequestValue(self::ID_STOCK_PRODUCT);
    }

    /**
     * @param string $value
     * @param string $label
     *
     * @return array
     */
    protected function formatFieldOption($value, $label)
    {
        return [
            'value' => $value,
            'label' => $label,
        ];
    }

}
