<?php

return [
    'access_key'  => [
        'required' => true,
        'type'     => 'anomaly.field_type.encrypted',
    ],
    'secret_key'  => [
        'required' => true,
        'type'     => 'anomaly.field_type.encrypted',
    ],
    'domain'      => [
        'required' => true,
        'type'     => 'anomaly.field_type.text',
    ],
    'region'      => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'options' => [
                'nyc3'      => 'New York'
            ],
        ],
    ],
    'bucket'      => [
        'required' => true,
        'type'     => 'anomaly.field_type.text',
    ],
    'prefix'      => 'anomaly.field_type.text',
    'cloud_front' => 'anomaly.field_type.text',
];
