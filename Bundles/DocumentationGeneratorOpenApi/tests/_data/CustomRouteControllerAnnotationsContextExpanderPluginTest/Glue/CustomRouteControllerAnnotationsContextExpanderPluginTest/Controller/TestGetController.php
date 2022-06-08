<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

class TestGetController
{
    /**
     * @Glue({
     *     "getResourceById": {
     *          "summary": [
     *              "Retrieves test by id."
     *          ],
     *          "parameters": [
     *              {
     *                  "ref": "acceptLanguage"
     *              },
     *              {
     *                  "name": "q",
     *                  "in": "query",
     *                  "description": "Description.",
     *                  "required": true
     *              }
     *          ],
     *          "responseAttributesClassName": "Generated\\Shared\\Transfer\\TestsRestAttributesTransfer",
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
