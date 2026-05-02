<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BroadcastIframeUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains($contentType, 'text/html') || !method_exists($response, 'getContent')) {
            return $response;
        }

        $status = $response->getStatusCode();
        $script = '<script>if(window.parent!==window){window.parent.postMessage({action:"iframe-nav",url:window.location.href,status:' . $status . '},"*");}</script>';
        $content = str_replace('</body>', $script . '</body>', $response->getContent());
        $response->setContent($content);

        return $response;
    }
}
