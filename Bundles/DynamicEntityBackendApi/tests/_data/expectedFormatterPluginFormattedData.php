<?php

return [
    'paths' =>
        [
            '/collection' =>
                [
                    'get' =>
                        [],
                    'post' =>
                        [
                            'requestBody' =>
                                [
                                    'content' =>
                                        [
                                            'application/json' =>
                                                [
                                                    'schema' =>
                                                        [
                                                            'type' => 'object',
                                                        ],
                                                ],
                                        ],
                                ],
                        ],
                    'put' =>
                        [],
                    'patch' =>
                        [],
                ],
            '/collection/{id}' =>
                [
                    'get' =>
                        [],
                ],
        ],
];
