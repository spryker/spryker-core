<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Discount\Communication\Form;

use SprykerFeature\Zed\Discount\Business\DiscountFacade;
use SprykerFeature\Zed\Discount\Dependency\Facade\DiscountFacadeInterface;
use SprykerFeature\Zed\Discount\Persistence\DiscountQueryContainer;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Discount\Persistence\Propel\SpyDiscountVoucherPool;
use SprykerFeature\Zed\Ui\Communication\Plugin\Form\Field;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class DiscountForm extends AbstractForm
{

    /**
     * @var DiscountQueryContainer
     */
    protected $queryContainer;

    /**
     * @var DiscountFacade
     */
    protected $discountFacade;

    /**
     * @param Request $request
     * @param QueryContainerInterface $queryContainer
     * @param DiscountFacadeInterface $discountFacade
     */
    public function __construct(
        Request $request,
        QueryContainerInterface $queryContainer,
        DiscountFacadeInterface $discountFacade
    ) {
        $this->discountFacade = $discountFacade;
        parent::__construct($request, $queryContainer);
    }

    /**
     * @return array|Field[]
     */
    public function addFormFields()
    {
        $this->addField('id_discount')
            ->setConstraints([
                new Assert\Optional([
                    new Assert\Type([
                        'type' => 'integer',
                    ]),
                ]),
            ]);

        $this->addField('fk_discount_voucher_pool')
            ->setAccepts($this->getVoucherPools())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getVoucherPools(), 'value'),
                    'message' => 'Please choose one of the given Voucher Pools',
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (int) $value : null;
            });

        $this->addField('display_name')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('description')
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('amount')
            ->setRefresh(false)
            ->setConstraints([
                $this->getAmountConstraints(),
            ]);

        $this->addField('is_active')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'bool',
                ]),
            ]);

        $this->addField('is_privileged')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'bool',
                ]),
            ]);

        $this->addField('valid_from')
            ->setRefresh(false);
//            ->setConstraints([
//                new Assert\Type([
//                    'type' => 'DateTime'
//                ]),
//            ]);

        $this->addField('valid_to')
            ->setRefresh(false);
//            ->setConstraints([
//                new Assert\Type([
//                    'type' => 'DateTime'
//                ]),
//            ]);

        $this->addField('calculator_plugin')
            ->setAccepts($this->getCalculatorPlugins())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getCalculatorPlugins(), 'value'),
                    'message' => 'Please choose one of the given Calculators',
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (string) $value : null;
            });

        $this->addField('collector_plugin')
            ->setAccepts($this->getCollectorPlugins())
            ->setRefresh(false)
            ->setConstraints([
                new Assert\Type([
                    'type' => 'string',
                ]),
                new Assert\Choice([
                    'choices' => array_column($this->getCollectorPlugins(), 'value'),
                    'message' => 'Please choose one of the given Collectors',
                ]),
                new Assert\NotBlank(),
            ])
            ->setValueHook(function ($value) {
                return $value ? (string) $value : null;
            });
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $data = [
            'calculator_plugin' => $this->getCalculatorPlugins()[0]['value'],
        ];

        $idDiscount = $this->stateContainer->getRequestValue('id_discount');
        $discountEntity = $this->queryContainer->queryDiscount()->findPk($idDiscount);

        if ($discountEntity) {
            $data = $discountEntity->toArray();
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getVoucherPools()
    {
        $voucherPools = $this->queryContainer->queryVoucherPool()->find();

        $data = [];

        /** @var SpyDiscountVoucherPool $voucherPool */
        foreach ($voucherPools as $voucherPool) {
            $data[] = [
                'value' => $voucherPool->getPrimaryKey(),
                'label' => $voucherPool->getName(),
            ];
        }

        if (empty($data)) {
            $data[] = [
                'value' => '',
                'label' => '',
            ];
        }

        return $data;
    }

    /**
     * @return array
     */
    protected function getCalculatorPlugins()
    {
        $calculatorNames = [];
        $calculatorPlugins = $this->discountFacade->getDiscountCalculators();

        foreach ($calculatorPlugins as $calculatorPlugin) {
            $calculatorNames[] = [
                'value' => $calculatorPlugin,
                'label' => $calculatorPlugin,
            ];
        }

        return $calculatorNames;
    }

    /**
     * @return array
     */
    protected function getCollectorPlugins()
    {
        $collectorNames = [];
        $collectorPlugins = $this->discountFacade->getDiscountCollectors();

        foreach ($collectorPlugins as $collectorPlugin) {
            $collectorNames[] = [
                'value' => $collectorPlugin,
                'label' => $collectorPlugin,
            ];
        }

        return $collectorNames;
    }

    /**
     * @return null|Assert\Range
     */
    protected function getAmountConstraints()
    {
        $constraints = [
            new Assert\Type([
                'type' => 'numeric',
            ]),
        ];

        $calculatorPluginName = $this->stateContainer->getLatestValue('calculator_plugin');

        $calculatorPlugin = $this->discountFacade->getCalculatorPluginByName($calculatorPluginName);

        if ($calculatorPlugin) {
            $constraints[] = new Assert\Range([
                'min' => $calculatorPlugin->getMinValue(),
                'max' => $calculatorPlugin->getMaxValue(),
            ]);
        }

        return $constraints;
    }

}
