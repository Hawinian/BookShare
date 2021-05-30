<?php
/**
 * Book controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\BookRepository;
use App\Repository\VoteRepository;
use App\Service\VoteService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VoteController.
 *
 * @IsGranted("ROLE_USER")
 */
class VoteController extends AbstractController
{
    /**
     * Vote service.
     *
     * @var \App\Service\VoteService
     */
    private $voteService;

    /**
     * VoteController constructor.
     *
     * @param \App\Service\VoteService $voteService Vote service
     */
    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP search
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}/vote",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="add_vote",
     * )
     */
    public function vote(Request $request, VoteRepository $voteRepository, Book $book, BookRepository $bookRepository): Response
    {
        $vote = new Vote();

        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vote->setUser($this->getUser());
            $vote->setBook($book);
            $userId = $this->getUser()->getId();
            $existingRates = $book->getVotes();
            foreach ($existingRates as $value) {
                $us = $value->getUser()->getId();
                if ($us == $userId) {
                    $voteRepository->delete($value);
                }
            }
            $voteRepository->save($vote);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('book_show', ['id' => $vote->getBook()->getId()]);
        }

        return $this->render(
            'vote/add.html.twig',
            ['form' => $form->createView(),
                'vote' => $vote,
                'book' => $book, ]
        );
    }
}
