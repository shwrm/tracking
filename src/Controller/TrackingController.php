<?php declare(strict_types=1);

namespace Shwrm\Tracking\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrackingController
{
    public function trackAction(Request $request, string $carrier)
    {
        $trackingCode = $request->query->get('trackingCode', null);

        if (null === $trackingCode) {
            return new JsonResponse('You have to provide a trackingCode parameter', 400);
        }

        return new JsonResponse($trackingCode);
    }
}
