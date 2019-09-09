<?php

namespace Pixney\DoAdapterExtension\Command;

use Aws\S3\S3Client;
use League\Flysystem\Filesystem;
use League\Flysystem\MountManager;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\FilesystemManager;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Anomaly\FilesModule\Disk\Adapter\AdapterFilesystem;
use Anomaly\EncryptedFieldType\EncryptedFieldTypePresenter;
use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;

/**
 * Class LoadDisk
 *
 *  @author Pixney AB <hello@pixney.com>
 *  @author William Åström <william@pixney.com>
 *
 *  @link https://pixney.com
 */
class LoadDisk
{
    /**
     * The disk interface.
     *
     * @var DiskInterface
     */
    protected $disk;

    /**
     * Create a new LoadDisk instance.
     *
     * @param DiskInterface $disk
     */
    public function __construct(DiskInterface $disk)
    {
        $this->disk = $disk;
    }

    /**
     * @param ConfigurationRepositoryInterface $configuration
     */
    public function handle(
        ConfigurationRepositoryInterface $configuration,
        FilesystemManager $filesystem,
        MountManager $manager,
        Repository $config
    ) {
        /* @var EncryptedFieldTypePresenter $key */
        $key = $configuration->presenter(
            'pixney.extension.do_adapter::access_key',
            $this->disk->getSlug()
        );

        if ($key) {
            $key = $key->decrypt();
        }

        /* @var EncryptedFieldTypePresenter $secret */
        $secret = $configuration->presenter(
            'pixney.extension.do_adapter::secret_key',
            $this->disk->getSlug()
        );

        if ($secret) {
            $secret = $secret->decrypt();
        }

        $prefix = $configuration->value(
            'pixney.extension.do_adapter::prefix',
            $this->disk->getSlug()
        );

        $bucket = $configuration->value(
            'pixney.extension.do_adapter::bucket',
            $this->disk->getSlug()
        );

        $region = $configuration->get(
            'pixney.extension.do_adapter::region',
            $this->disk->getSlug()
        )->getValue();

        $domain = $configuration->get(
            'pixney.extension.do_adapter::domain',
            $this->disk->getSlug()
        )->getValue();

        $config->set(
            'filesystems.disks.' . $this->disk->getSlug(),
            [
                'driver' => 's3',
                'key'    => $key,
                'secret' => $secret,
                'region' => $region,
                'bucket' => $bucket,
                'prefix' => $prefix,
            ]
        );
        // https://pixney.nyc3.cdn.digitaloceanspaces.com/.DS_Store
        $baseUrl = 'https://'
            . $domain
            . '.' . $region
            . '.digitaloceanspaces.com/'
            . implode(
                '/',
                array_filter([$bucket, $prefix])
            );

        $client = new S3Client([
                'credentials' => [
                    'key'    => $key,
                    'secret' => $secret,
                ],
                'region'   => $region,
                'version'  => 'latest',
                'endpoint' => 'https://' . $region . '.digitaloceanspaces.com',
            ]);

        $adapter = new AwsS3Adapter($client, $bucket);

        $driver = new AdapterFilesystem($this->disk, $adapter, ['base_url'=>$baseUrl]);

        $manager->mountFilesystem($this->disk->getSlug(), $driver);

        $filesystem->extend(
            $this->disk->getSlug(),
            function () use ($driver) {
                return $driver;
            }
        );

        //$filesystem = new Filesystem($adapter);

        // 's3' => [
        //     'driver' => env('S3_DRIVER', 's3'),
        //     'endpoint' => env('S3_ENDPOINT'),
        //     'version' => env('S3_VERSION', 'latest'),
        //     'key' => env('S3_KEY'),
        //     'secret' => env('S3_SECRET'),
        //     'region' => env('S3_REGION'),
        //     'bucket' => env('S3_BUCKET'),
        //     'root' => env('S3_ROOT'),
        //     ],

        // $driver = new AdapterFilesystem(
        //     $this->disk,
        //     new AwsS3Adapter(
        //         $client = new S3Client(
        //             [
        //                 'credentials' => [
        //                     'key'    => $key,
        //                     'secret' => $secret,
        //                 ],
        //                 'endpoint'    => 'https://nyc3.digitaloceanspaces.com',
        //                 'region'      => $region,
        //                 'version'     => '2006-03-01',
        //             ]
        //         ),
        //         $bucket,
        //         $prefix
        //     ),
        //     [
        //         'base_url' => $baseUrl,
        //     ]
        // );

        // $manager->mountFilesystem($this->disk->getSlug(), $driver);

        // $filesystem->extend(
        //     $this->disk->getSlug(),
        //     function () use ($driver) {
        //         return $driver;
        //     }
        // );
    }
}
