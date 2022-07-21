<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

class TestResourceController
{
    /**
     * @Glue({
     *      "getCollection": {
     *          "summary": [
     *              "Retrieves collection of tests."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAttributesTransfer"
     *      }
     * })
     *
     * @return void
     */
    public function getEmptyResponseAction(): void
    {
    }

    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves store by id."
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\RestAttributesTransfer",
     *          "responses": {
     *              "404": "Test not found."
     *          }
     *     }
     * })
     *
     * @return void
     */
    public function getAction(): void
    {
    }
}
