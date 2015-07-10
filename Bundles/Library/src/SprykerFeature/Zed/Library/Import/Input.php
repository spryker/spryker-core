<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

class Input
{

    /**
     * @var string
     */
    protected $source;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $source
     * @param array $data
     */
    public function __construct($source, $data)
    {
        $this->source = $source;
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

}
