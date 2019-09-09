<?php

namespace Pixney\DoAdapterExtension;

use Pixney\DoAdapterExtension\Command\LoadDisk;
use Anomaly\FilesModule\Disk\Contract\DiskInterface;
use Anomaly\FilesModule\Disk\Adapter\AdapterExtension;
use Anomaly\FilesModule\Disk\Adapter\Contract\AdapterInterface;

/**
 * Class DoAdapterExtension
 *
 *  @author Pixney AB <hello@pixney.com>
 *  @author William Åström <william@pixney.com>
 *
 *  @link https://pixney.com
 */
class DoAdapterExtension extends AdapterExtension implements AdapterInterface
{
    /**
     * This module provides the s3
     * storage adapter for the files module.
     *
     * @var string
     */
    protected $provides = 'anomaly.module.files::adapter.do';

    /**
     * Load the disk.
     *
     * @param DiskInterface $disk
     */
    public function load(DiskInterface $disk)
    {
        $this->dispatch(new LoadDisk($disk));
    }

    /**
     * Validate adapter configuration.
     *
     * @param array $configuration
     * @return bool
     */
    public function validate(array $configuration)
    {
        return true;
    }
}
