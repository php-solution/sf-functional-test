<?php

declare(strict_types=1);

namespace PhpSolution\FunctionalTest\TestCase\Traits;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\DependencyInjection\ContainerInterface;

trait SpoolTrait
{
    /**
     * We need to purge the spool between each scenario
     */
    public function purgeSpool(): void
    {
        if (is_dir($this->getSpoolDir())) {
            $filesystem = new Filesystem();
            $finder = $this->getSpooledEmails();

            /** @var File $file */
            foreach ($finder as $file) {
                $filesystem->remove($file->getRealPath());
            }
        }
    }

    public function getSpooledEmails(): Finder
    {
        $finder = new Finder();
        $spoolDir = $this->getSpoolDir();
        $finder->files()->in($spoolDir);

        return $finder;
    }

    public function getEmailContent(string $file): string
    {
        return unserialize(file_get_contents($file));
    }

    protected function getSpoolDir(): string
    {
        return self::getContainer()->getParameter('swiftmailer.spool.default.file.path');
    }

    abstract protected static function getContainer(): ContainerInterface;
}
