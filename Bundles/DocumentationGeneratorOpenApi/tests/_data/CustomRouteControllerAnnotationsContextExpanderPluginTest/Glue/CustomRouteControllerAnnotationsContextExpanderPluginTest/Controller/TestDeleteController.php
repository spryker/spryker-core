<?php
// phpcs:disable PSR1.Classes.ClassDeclaration.MissingNamespace

class TestDeleteController
{
    /**
     * @Glue({
     *     "delete": {
     *          "summary": [
     *              "Deletes tests resource."
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
    public function deleteAction(): void
    {
    }
}
