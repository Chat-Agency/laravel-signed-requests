<?php

namespace ChatAgency\LaravelSignedRequests\Http\Middleware;

use Closure;
use ChatAgency\LaravelSignedRequests\SignedRequestService;

class ValidateSignedRequest
{
    /**
     * Define request type for this middleware. You need a specific middleware per request type.
     * You need to have this defined in the config for signed-requests.types and
     * signed-requests.secrets.type for the defined type used here.
     */
    public const REQUEST_TYPE = 'request';

    /**
     * Handles the incoming request.
     *
     * @param  mixed $request
     * @param  Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isValidSig = false;
        $params = collect($request->all());
        $signature_header = config('signed-requests.signature_header');
        $secret = config('signed-requests.secrets.'.self::REQUEST_TYPE);
        $signature = $request->header($signature_header);

        // key params are present and we can start the request for known types
        if ($request->headers->has($signature_header)) {
            $isValidSig = (new SignedRequestService())
                ->setType(self::REQUEST_TYPE)
                ->setPayload(json_encode($params->all(), 0, 10))
                ->setSignature($signature)
                ->setSecret($secret)
                ->isValid();
        } else {
            // key params were not present, we log some stuff
            logger()->error(print_r([
                'request' => $request->all(),
                'params' => $params,
                'headers' => $request->headers->all(),
            ], 1));
            // then abort the request, it was malformed
            abort(401, 'Your request is missing some key parameters. Send them over on your next attempts.');
        }

        if ($isValidSig) {
            // everything is peachy!
            return $next($request);
        } else {
            // You shall not pass! - Gandalf the Middlewarewise.
            abort(403, 'Whatever you tried to do; You can\'t.');
        }

        $response = $next($request);

        $response->header('x-signature-valid', 'yes');

        return $response;
    }
}
