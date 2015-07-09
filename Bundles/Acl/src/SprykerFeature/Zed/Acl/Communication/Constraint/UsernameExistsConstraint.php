<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Acl\Communication\Constraint;

use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Shared\User\Messages\Messages;
use Symfony\Component\Validator\Constraint;

class UsernameExistsConstraint extends Constraint
{

    public $message = Messages::USER_EXISTS_ERROR;

    /**
     * @var string
     */
    protected $username;

    /** @var  int */
    protected $idUser;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param string $username
     * @param int $idUser
     * @param AutoCompletion $locator
     * @param null $options
     */
    public function __construct(
        $username,
        $idUser,
        $locator,
        $options = null
    ) {
        $this->username = $username;
        $this->idUser = $idUser;
        $this->locator = $locator;
        parent::__construct($options);
    }

    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return AutoCompletion
     */
    public function getLocator()
    {
        return $this->locator;
    }

}
