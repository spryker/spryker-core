<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\UiExample\Communication\Form;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use SprykerFeature\Zed\Ui\Library\Constraints\Type;
use SprykerFeature\Zed\UiExample\Communication\UiExampleDependencyContainer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

class UiExampleForm extends AbstractForm
{

    /**
     * @var UiExampleDependencyContainer
     */
    protected $dependencyContainer;

    public function __construct(
        Request $request,
        UiExampleDependencyContainer $dependencyContainer,
        AbstractQueryContainer $queryContainer = null
    ) {
        $this->dependencyContainer = $dependencyContainer;

        parent::__construct($request, $queryContainer);
    }

    public function addFormFields()
    {
        $this->addField('id_ui_example')
            ->setConstraints([
                new Assert\Type([
                    'type' => 'integer',
                ]),
            ]);

        $this->addField('column_for_string')
            ->setConstraints([
                new Assert\Required([
                    new Assert\Type([
                        'type' => 'string',
                    ]),
                    new Assert\NotBlank(),
                ]),
            ]);

        $this->addField('column_for_boolean')
            ->setConstraints([
                new Type([
                    'type' => 'boolean',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addField('vehicle')
            ->setRefresh(true)
            ->setAccepts([
                [
                    'value' => 'Car',
                    'label' => 'Car',
                ],
                [
                    'value' => 'Copter',
                    'label' => 'Copter',
                ],
            ])
            ->setConstraints([
                new Type([
                    'type' => 'string',
                ]),
            ]);

        $this->addField('column_for_datetime')
            ->setConstraints([
                new Type([
                    'type' => 'string',
                ]),
                new Assert\NotBlank(),
            ]);

        $this->addSubForm('vehicle_specs')
            ->setForm($this->getSubForm());
    }

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [
            'column_for_datetime' => $this->stateContainer->getRequestValue('column_for_string'),
            'vehicle' => 'Copter',
        ];
    }

    /**
     * @return mixed
     */
    protected function getSubForm()
    {
        if ($this->stateContainer->getLatestValue('vehicle') === 'Car') {
            $form = $this->dependencyContainer->getCarForm($this->stateContainer->getRequest());
        } else {
            $form = $this->dependencyContainer->getCopterForm($this->stateContainer->getRequest());
        }

        return $form;
    }

}
