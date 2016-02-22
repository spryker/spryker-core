<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Library\Session;

use Spryker\Shared\Transfer\TransferInterface;
use Symfony\Component\HttpFoundation\Session\Session;

class TransferSession
{

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    protected $session;

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param string $name
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return void
     */
    public function set($name, TransferInterface $transferObject)
    {
        $this->session->set($name, $transferObject->toArray(false));
    }

    /**
     * @param string $name
     * @param \Spryker\Shared\Transfer\TransferInterface $transferObject
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function get($name, TransferInterface $transferObject)
    {
        $transferArray = $this->session->get($name);
        if (!empty($transferArray)) {
            $transferObject->fromArray($transferArray, true);
        }

        return $transferObject;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has($name)
    {
        return $this->session->has($name);
    }

    /**
     * @param string $name
     *
     * @return mixed The removed value or null when it does not exist
     */
    public function remove($name)
    {
        return $this->session->remove($name);
    }

}
