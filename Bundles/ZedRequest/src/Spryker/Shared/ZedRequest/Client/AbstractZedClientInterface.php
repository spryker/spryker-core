<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */
namespace Spryker\Shared\ZedRequest\Client;

interface AbstractZedClientInterface
{

    /**
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer);

    /**
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse();

}
