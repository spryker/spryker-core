<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

interface ProcessInterface
{

    /**
     * @return string
     */
    public function getType();

    /**
     * @return ValidatorInterface
     */
    public function getValidator();

    /**
     * @return WriterInterface
     */
    public function getWriter();

}
