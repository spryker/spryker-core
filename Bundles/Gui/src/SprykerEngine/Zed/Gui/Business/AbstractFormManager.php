<?php
/**
 * Created by PhpStorm.
 * User: vsevoloddolgopolov
 * Date: 07/07/15
 * Time: 12:23
 */

namespace SprykerEngine\Zed\Gui\Business;

use SprykerFeature\Zed\Product\Communication\Form\Type\AutosuggestType;
use Symfony\Component\Form\FormFactory;

use Symfony\Component\Form\Forms;
use Symfony\Component\Form\Extension\HttpFoundation\HttpFoundationExtension;
use Symfony\Component\Form\Extension\Validator\Type\FormTypeValidatorExtension;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\Extension\Core\CoreExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraint;

class ConstraintBuilder
{
    protected $constraints = null;

    public static function getInstance() {
        return new static();
    }

    public function addNotBlank()
    {
        return $this->add(new NotBlank());
    }

    public function addLength($options)
    {
        return $this->add(new Length($options));
    }

    public function add(Constraint $constraint)
    {
        $this->constraints[] = $constraint;
        return $this;
    }

    public function getConstraints()
    {
        return $this->constraints;
    }
}


abstract class AbstractFormManager
{
    /** @var FormFactory  */
    private $formFactory;

    protected $form = null;

    protected static $instance = null;

    protected $validation_rules = [];


    public function __construct(FormFactory $formFactory)
    {

        $this->formFactory = $formFactory;
        $this->form = $formFactory->create();
    }

    public function add($name, $type, $options = array())
    {
        if ($options instanceof ConstraintBuilder) {
            $options = ['constraints' => $options->getConstraints()];
        }
        $this->form->add($name, $type, $options);
        return $this;
    }

    /**
     * @return mixed
     */
    public function render()
    {
        return $this->form->createView();
    }

    public function handleRequestAndData($request)
    {
        $this->form->handleRequest($request);
        if ($this->form->isSubmitted() && $this->form->isValid()) {
            return $this->form->getData();
        }

        return false;
    }

    public function getErrors()
    {
        return $this->form->getErrors(true, false);
    }

    public function addText($name, $options = array())
    {

        $this->add($name, 'text', $options);
        return $this;
    }

    public function addChoice($name, $options = array())
    {
        $this->add($name, 'choice', $options);
        return $this;
    }

    public function addSubmit($name = 'submit', $options = array())
    {
        $this->add($name, 'submit', $options);
        return $this;
    }

    public function setData($data)
    {
        $this->form->setData($data);
        return $this;
    }
}
