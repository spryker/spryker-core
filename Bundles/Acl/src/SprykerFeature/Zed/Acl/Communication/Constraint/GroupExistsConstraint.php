<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Constraint;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\Acl\Messages\Messages;
use Symfony\Component\Validator\Constraint;

class GroupExistsConstraint extends Constraint
{

    public $message = Messages::GROUP_EXISTS_ERROR;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $idGroup;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param string $name
     * @param int $idGroup
     * @param AutoCompletion $locator
     * @param null $options
     */
    public function __construct(
        $name,
        $idGroup,
        $locator,
        $options = null
    ) {
        $this->name = $name;
        $this->idGroup = $idGroup;
        $this->locator = $locator;
        parent::__construct($options);
    }

    /**
     * @return int
     */
    public function getGroupId()
    {
        return $this->idGroup;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return AutoCompletion
     */
    public function getLocator()
    {
        return $this->locator;
    }

}
