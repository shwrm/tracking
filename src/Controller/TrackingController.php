<?php declare(strict_types=1);

namespace Shwrm\Tracking\Controller;

use Shwrm\Tracking\Exception\AccessToCarrierDenied;
use Shwrm\Tracking\Exception\NotImplementedAdapterException;
use Shwrm\Tracking\Exception\UnknownStatusException;
use Shwrm\Tracking\Integrations\Adapters\AdapterResolver;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TrackingController
{
    /** @var AdapterResolver */
    private $adapterResolver;

    public function __construct(AdapterResolver $adapterResolver)
    {
        $this->adapterResolver = $adapterResolver;
    }

    public function trackAction(Request $request, string $carrier, string $id)
    {
        try {
            $adapter = $this->adapterResolver->resolve($carrier);
        } catch (NotImplementedAdapterException $exception) {
            return new JsonResponse(sprintf('%s carrier has not been implemented yet', $carrier), 400);
        }

        $parameters = $request->query->all();
        $errors     = $adapter->validate($parameters);

        if (false === $errors->isEmpty()) {
            return new JsonResponse($errors->__toString(), 400);
        }

        try {
            $status = $adapter->track($id, $parameters);
        } catch (UnknownStatusException $exception) {
            return new JsonResponse('Cannot fetch status', 400);
        } catch (AccessToCarrierDenied $exception) {
            return new JsonResponse($exception->getMessage(), 400);
        }

        return new JsonResponse(['status' => $status]);
    }
}
