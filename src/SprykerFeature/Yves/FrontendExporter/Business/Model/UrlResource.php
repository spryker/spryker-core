<?php

namespace SprykerFeature\Yves\FrontendExporter\Business\Model;

/**
 * Class UrlResource
 * @package SprykerFeature\Yves\FrontendExporter\Business\Model
 */
class UrlResource
{
    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $referenceKey;

    /**
     * @param $referenceKey
     * @param $type
     */
    public function __construct($referenceKey = null, $type = null)
    {
        $this->referenceKey = $referenceKey;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getReferenceKey()
    {
        return $this->referenceKey;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param array $data
     * @return $this UrlResource
     */
    public function fromArray(array $data)
    {
        $this->type = $data['type'];
        $this->referenceKey = $data['reference_key'];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'type' => $this->type,
            'reference_key' => $this->referenceKey,
        ];
    }
}
