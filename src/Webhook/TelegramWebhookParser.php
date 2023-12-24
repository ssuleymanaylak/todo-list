<?php

namespace App\Webhook;

use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;

class TelegramWebhookParser extends AbstractRequestParser
{
    protected function getRequestMatcher(): RequestMatcherInterface
    {
        // these define the conditions that the incoming webhook request
        // must match in order to be handled by this parser
        return new ChainRequestMatcher([
            new IsJsonRequestMatcher(),
            new MethodRequestMatcher('POST'),
        ]);
    }

    protected function doParse(Request $request, string $secret): ?RemoteEvent
    {
        error_log($request->getContent());
    }
}
