<?php

declare(strict_types=1);

namespace OBMS\Composer\Installer;

use Composer\Installer\LibraryInstaller;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use InvalidArgumentException;

class Installer extends LibraryInstaller
{
    protected $locations = [
        'product'        => 'app/Products/',
        'paymentgateway' => 'app/PaymentGateways/',
        'theme'          => 'resources/themes/',
    ];

    protected $supportedTypes = [
        'obms' => 'OBMSInstaller',
    ];

    /**
     * {@inheritDoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $extra         = $package->getExtra();
        $type          = $package->getType();
        $supportedType = $this->supportedType($type);

        if ($supportedType === false) {
            throw new InvalidArgumentException(
                'Sorry the package type of this package is not supported.'
            );
        }

        $location = str_replace($supportedType . '-', '', $type);

        return $this->locations[$location] . '/' . $extra['dir'] . '/';
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        if (!$repo->hasPackage($package)) {
            throw new InvalidArgumentException(
                sprintf('Package is not installed: %s', $package)
            );
        }

        $repo->removePackage($package);

        $installPath = $this->getInstallPath($package);
        $this->io->write(
            sprintf(
                'Deleting %s - %s',
                $installPath,
                $this->filesystem->removeDirectory($installPath)
                ? '<comment>deleted</comment>'
                : '<error>not deleted</error>'
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports($packageType)
    {
        $supportedType = $this->supportedType($packageType);

        if ($supportedType === false) {
            return false;
        }

        foreach ($this->locations as $type => $path) {
            if ($supportedType . '-' . $type === $packageType) {
                return true;
            }
        }

        return false;
    }

    /**
     * Find the matching installer type.
     *
     * @param string $type
     *
     * @return bool|string
     */
    protected function supportedType($type)
    {
        $supportedType  = false;
        $stringPosition = strpos($type, '-');
        $baseType       = substr($type, 0, is_numeric($stringPosition) ? $stringPosition : null);

        if (array_key_exists($baseType, $this->supportedTypes)) {
            $supportedType = $baseType;
        }

        return $supportedType;
    }
}
