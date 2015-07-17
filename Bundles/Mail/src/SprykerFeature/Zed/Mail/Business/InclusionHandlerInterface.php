<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Mail\Business;

interface InclusionHandlerInterface
{

    /**
     * @param string $path
     *
     * @return string
     */
    public function guessType($path);

    /**
     * @param string $path
     *
     * @return string
     */
    public function getFilename($path);

    /**
     * @param string $path
     *
     * @return string
     */
    public function encodeBase64($path);

}
