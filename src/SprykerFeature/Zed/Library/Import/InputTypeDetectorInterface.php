<?php
namespace SprykerFeature\Zed\Library\Import;

interface InputTypeDetectorInterface extends TypeDetectorInterface
{
    /**
     * @param Input $input
     * @return string The type
     * @throws Exception\ImportTypeNotDetectedException
     */
    public function detect(Input $input);
}
