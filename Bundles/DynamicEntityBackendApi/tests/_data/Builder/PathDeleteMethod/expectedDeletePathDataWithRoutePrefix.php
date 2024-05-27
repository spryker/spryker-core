<?php

return [
  'paths' =>
   [
    '/dynamic-entity-prefix/test-resource' =>
     [
      'delete' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'delete-collection-dynamic-api-test-resource',
        'summary' => 'Delete collection of test-resource',
        'parameters' =>
         [
          0 =>
           [
            'name' => 'filter',
            'in' => 'query',
            'description' => 'Parameter is used to filter items by specified values.',
            'required' => false,
            'style' => 'deepObject',
            'explode' => true,
            'schema' =>
             [
              'type' => 'object',
              'properties' =>
               [
                'test-resource.test' =>
                 [
                  'type' => 'string',
                ],
              ],
            ],
          ],
          1 =>
           [
            'name' => 'Content-Type',
            'in' => 'header',
            'description' => 'Content type of request body.',
            'required' => true,
            'schema' =>
             [
              'type' => 'string',
              'example' => 'application/json',
            ],
          ],
          2 =>
           [
            'name' => 'Accept',
            'in' => 'header',
            'description' => 'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.',
            'required' => true,
            'schema' =>
             [
              'type' => 'string',
              'example' => 'application/json',
            ],
          ],
        ],
        'responses' =>
         [
          204 =>
           [
            'description' => 'No content.',
          ],
          403 =>
           [
            'description' => 'Unauthorized request.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  '$ref' => '#/components/schemas/RestErrorMessage',
                ],
              ],
            ],
          ],
          405 =>
           [
            'description' => 'Method not allowed.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  '$ref' => '#/components/schemas/RestErrorMessage',
                ],
              ],
            ],
          ],
          'default' =>
           [
            'description' => 'An error occurred.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  '$ref' => '#/components/schemas/RestErrorMessage',
                ],
              ],
            ],
          ],
        ],
      ],
    ],
    '/dynamic-entity-prefix/test-resource/{id}' =>
     [
      'delete' =>
       [
        'tags' =>
         [
          0 => 'dynamic-entity-test-resource',
        ],
        'operationId' => 'delete-entity-dynamic-api-test-resource',
        'summary' => 'Delete entity of test-resource',
        'parameters' =>
         [
          0 =>
           [
            'name' => 'id',
            'in' => 'path',
            'required' => true,
            'description' => 'ID of entity test-resource',
            'schema' =>
             [
              'type' => 'integer',
            ],
          ],
          1 =>
           [
            'name' => 'Content-Type',
            'in' => 'header',
            'description' => 'Content type of request body.',
            'required' => true,
            'schema' =>
             [
              'type' => 'string',
              'example' => 'application/json',
            ],
          ],
          2 =>
           [
            'name' => 'Accept',
            'in' => 'header',
            'description' => 'The Accept request HTTP header indicates which content types, expressed as MIME types, the client is able to understand.',
            'required' => true,
            'schema' =>
             [
              'type' => 'string',
              'example' => 'application/json',
            ],
          ],
        ],
        'responses' =>
         [
          204 =>
           [
            'description' => 'No content.',
          ],
          403 =>
           [
            'description' => 'Unauthorized request.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  '$ref' => '#/components/schemas/RestErrorMessage',
                ],
              ],
            ],
          ],
          405 =>
           [
            'description' => 'Method not allowed.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  '$ref' => '#/components/schemas/RestErrorMessage',
                ],
              ],
            ],
          ],
          'default' =>
           [
            'description' => 'An error occurred.',
            'content' =>
             [
              'application/json' =>
               [
                'schema' =>
                 [
                  '$ref' => '#/components/schemas/RestErrorMessage',
                ],
              ],
            ],
          ],
        ],
      ],
    ],
  ],
];
