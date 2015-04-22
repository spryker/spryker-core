<?php

namespace SprykerFeature\Shared\Document\Transfer;

class DocumentType
{

    /**
     * @var int
     */
    protected $idDocumentType = null;
    protected $_idDocumentType = ['is_int'];

    /**
     * @var int
     */
    protected $name = null;
    protected $_name = [];

    /**
     * @var string
     */
    protected $type = null;
    protected $_type = [];

    /**
     * @var string
     */
    protected $createdAt = null;
    protected $_createdAt = [];

    /**
     * @var string
     */
    protected $updatedAt = null;
    protected $_updatedAt = [];
}
