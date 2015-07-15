<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

interface InputTypeDetectorInterface extends TypeDetectorInterface
{

    /**
     * @param Input $input
     *
     * @throws Exception\ImportTypeNotDetectedException
     *
     * @return string The type
     */
    public function detect(Input $input);

}
