<?php

return [
    'access_key'  => [
        'label'        => 'Access Key',
        'instructions' => 'Enter your S3 access key.',
    ],
    'secret_key'  => [
        'label'        => 'Secret Key',
        'instructions' => 'Enter your S3 secret key.',
    ],
    'region'      => [
        'label'        => 'Region',
        'instructions' => 'Choose the region your S3 bucket resides in.',
    ],
    'bucket'      => [
        'label'        => 'Bucket',
        'instructions' => 'Enter your S3 bucket name.',
    ],
    'prefix'      => [
        'label'        => 'Prefix',
        'instructions' => 'Prefixes are useful for disks that share a single S3 bucket.<br><strong>https://{region}.amazonaws.com/{bucket}/{prefix}/{folder}/{filename}</strong>',
    ],
    'cloud_front' => [
        'label'        => 'CloudFront Domain',
        'instructions' => 'Specify the CloudFront domain if available.',
        'placeholder'  => 'a17v5vkqn7ysat.cloudfront.net',
    ],
];
