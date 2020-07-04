<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $_userRepository;
    private $_router;
    private $_csrfTokenManager;


    public function __construct(UserRepository $userRepository, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->_userRepository = $userRepository;
        $this->_router = $router;
        $this->_csrfTokenManager = $csrfTokenManager;
    }


    public function supports(Request $request)
    {
        return $request->attributes->get('_route') === 'login'
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $data = $request->request->get('user_form');
        $credentials = [
            'username' => $data['username'],
            'password' => $data['password'],
            'csrf_token' => $data['_csrf_token'],
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['username']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
//        if (!$this->_csrfTokenManager->isTokenValid($token)) {
//            throw new InvalidCsrfTokenException();
//        }

        return $this->_userRepository->findOneBy(['username' => $credentials['username'], 'password' => $credentials['password']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true; //todo
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse($this->_router->generate('index'));
    }

    protected function getLoginUrl()
    {
        return $this->_router->generate('login');
    }
}
