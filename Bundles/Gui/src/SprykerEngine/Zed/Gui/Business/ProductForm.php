<?php
/**
 * Created by PhpStorm.
 * User: vsevoloddolgopolov
 * Date: 07/07/15
 * Time: 12:20
 */

namespace SprykerEngine\Zed\Gui\Business;

use SprykerEngine\Zed\Gui\Business\AbstractFormManager;
use SprykerEngine\Zed\Gui\Business\ConstraintBuilder;
use SprykerFeature\Zed\Product\Communication\Form\Type\AutosuggestType;

class ProductForm extends AbstractFormManager
{

    public function createForm($data)
    {
        return $this->addText('test', ConstraintBuilder::getInstance()
                ->addNotBlank()
                ->addLength(['min'=>5])
            )
            ->addChoice('age', ['label'=> 'Your age'])
            ->add('interests', new AutosuggestType())
            ->addSubmit()
            ->setData($data);
    }

}
