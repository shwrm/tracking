<?php declare(strict_types=1);

namespace Shwrm\Tracking\Controller;

use Shwrm\Tracking\Exception\NotImplementedAdapterException;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\AdapterResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HealthCheckController
{
    public function heartbeatAction()
    {
        return new Response();
    }
}
