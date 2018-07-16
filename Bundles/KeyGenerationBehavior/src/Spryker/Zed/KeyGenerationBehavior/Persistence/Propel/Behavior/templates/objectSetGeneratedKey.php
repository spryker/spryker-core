/**
* @return void
*/
protected function setGeneratedKey()
{
    $uuidGenerateUtilService = $this->getUuidGeneratorService();
    $name = <?php echo $name; ?>;
    $key = $uuidGenerateUtilService->generateUuid5($name);
    $this->setApiKey($key);
}