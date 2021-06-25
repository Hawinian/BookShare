<?php
/**
 * Acces Denied Handler.
 */

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Class AccessDeniedHandler.
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    private $urlGenerator;
    private $session;

    /**
     * AccessDeniedHandler constructor.
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, SessionInterface $session)
    {
        $this->urlGenerator = $urlGenerator;
        $this->session = $session;
    }

    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        // ...

        return new RedirectResponse($this->urlGenerator->generate('book_index'));
        //return new Response($content, 403);
    }
}
