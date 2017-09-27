<?php


namespace Spryker\Yves\StepEngine\Form;


use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface FormCollectionHandlerDataAwareInterface
{

    /**
     * @param AbstractTransfer $dataTransfer
     *
     * @return void
     */
    public function applyDataTransfer(AbstractTransfer $dataTransfer);

}