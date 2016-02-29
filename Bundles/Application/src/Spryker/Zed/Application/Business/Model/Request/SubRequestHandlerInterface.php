<?php
namespace Spryker\Zed\Application\Business\Model\Request;

use Symfony\Component\HttpFoundation\Request;

interface SubRequestHandlerInterface
{
    /**
     * @param Request $request
     * @param string $url
     * @param array $additionalSubRequestParameters
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handleSubRequest(Request $request, $url, array $additionalSubRequestParameters = []);
}
