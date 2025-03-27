<?php

declare(strict_types=1);

namespace OBMS\Composer\Installer;

use Composer\Installers\BaseInstaller;

class OBMSInstaller extends BaseInstaller
{
    protected $locations = [
        'product'        => 'app/Products/{$name}/',
        'paymentgateway' => 'app/PaymentGateways/{$name}/',
    ];
}
