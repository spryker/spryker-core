/**
* @return \Spryker\Service\UtilUuidGenerator\UtilUuidGeneratorServiceInterface
*/
protected function getUuidGeneratorService()
{
    if ($this->_locator === null) {
        $this->_locator = \Spryker\Zed\Kernel\Locator::getInstance();
    }
    $uuidGenerationService = $this->_locator->utilUuidGenerator()->service();

    return $uuidGenerationService;
}
