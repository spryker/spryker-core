<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Sales\Communication\Form;

use SprykerFeature\Zed\Ui\Dependency\Form\AbstractForm;

class DemoCommentForm extends AbstractForm
{

    /**
     * @return array
     */
    protected function getDefaultData()
    {
        return [];
    }

    public function addFormFields()
    {
        $this->addField('message')
            ->setLabel('Message')
            ->setRefresh(false)
        ;
    }

}
