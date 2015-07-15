<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Required;
use Symfony\Component\Validator\Constraints\Type;

class CommentForm extends AbstractForm
{

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        $salesOrderId = $this->stateContainer->getRequest()->get('orderId');

        return [
            'username' => 'Spryker',
            'fk_sales_order' => $salesOrderId,
        ];
    }

    /**
     * @return array
     */
    public function addFormFields()
    {
        $fields = [];
        $fields[] = $this->addField('message')
            ->setLabel('Add new comment')
            ->setRefresh(false)
            ->setConstraints([
                new Required([
                    new Type([
                        'type' => 'string',
                        'message' => 'Please add comment',
                    ]),
                    new NotBlank(),
                ]),
            ])
        ;
        $fields[] = $this->addField('fk_sales_order')
            ->setRefresh(false)
        ;
        $fields[] = $this->addField('username')
            ->setRefresh(false)
        ;
    }

}
