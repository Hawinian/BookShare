<?php
/**
 * Search controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Form\SearchType;
use App\Repository\BookRepository;
use App\Service\BookService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class SearchController.
 *
 * @Route("/search")
 */
class SearchController extends AbstractController
{
    /**
     * Book service.
     *
     * @var \App\Service\BookService
     */
    private $bookService;

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP search
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET", "POST"},
     *     name="search_index",
     * )
     */
    public function search(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator, BookService $bookService): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Book::class);
            $title = $form->getData();
            $existingBook = $repository->findOneBy(['title' => $title]);

            if ($existingBook) {
                dump($title);
            } else {
                dump($title);
            }
        }

        return $this->render(
            'search/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
