<?php

namespace App\Security;

use App\Repository\ApiKeyRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class ApiKeyAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private ApiKeyRepository $apiKeyRepository
    )
    {
        //
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-API-KEY');
    }

    public function authenticate(Request $request): Passport
    {
        $token = $request->headers->get('X-API-KEY');
        $apiUser = $this->apiKeyRepository->findOneBy([
            'apiKey' => $token
        ]);

        if (! $apiUser) {
            throw new AuthenticationException('Invalid API Key');
        }

        $user = new UserBadge($apiUser->getApiKey(), function($apiKey) {
                return $this->apiKeyRepository->findOneBy([
                    'apiKey' => $apiKey
                ]);
        });

        return new SelfValidatingPassport($user);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        return new JsonResponse([
            'message' => $exception->getMessage()
        ], Response::HTTP_UNAUTHORIZED);
    }
}
