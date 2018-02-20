<?php

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Trait SpoolTrait
 */
trait SpoolTrait
{
    /**
     * We need to purge the spool between each scenario
     */
    public function purgeSpool()
    {
        $filesystem = new Filesystem();
        $finder = $this->getSpooledEmails();

        /** @var File $file */
        foreach ($finder as $file) {
            $filesystem->remove($file->getRealPath());
        }
    }

    /**
     * @return Finder
     */
    public function getSpooledEmails()
    {
        $finder = new Finder();
        $spoolDir = $this->getSpoolDir();
        $finder->files()->in($spoolDir);

        return $finder;
    }

    /**
     * @param $file
     *
     * @return string
     */
    public function getEmailContent($file)
    {
        return unserialize(file_get_contents($file));
    }

    /**
     * @return string
     */
    protected function getSpoolDir()
    {
        return $this->getContainer()->getParameter('swiftmailer.spool.default.file.path');
    }

    /**
     * @return ContainerInterface
     */
    abstract function getContainer(): ContainerInterface;
}