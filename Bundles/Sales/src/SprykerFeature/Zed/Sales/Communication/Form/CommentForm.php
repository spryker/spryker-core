<?php

namespace SprykerFeature\Sales\Communication\Form;

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
        return [];
    }

    /**
     * @return array
     */
    public function addFormFields()
    {
        $fields = [];
        $fields[] = $this->addField('comment')
            ->setLabel('Add new comment')
            ->setRefresh(false)
            ->setConstraints([
                new Required([
                    new Type([
                        'type' => 'string'
                    ]),
                    new NotBlank()
                ])
            ]);

        return $fields;
    }
}
