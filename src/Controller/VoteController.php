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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BookController.
 */
class VoteController extends AbstractController
{
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
    public function vote(UserInterface $loggedUser, Request $request, VoteRepository $voteRepository, Book $book, BookRepository $bookRepository): Response
    {
        $vote = new Vote();
        //$vote->setBook($book);

        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vote->setUser($this->getUser());
            $vote->setBook($book);
            $userId = $loggedUser->getId();
            $existingRates = $book->getVotes();
            foreach ($existingRates as $value) {
                $us = $value->getUser()->getId();
                if ($us == $userId) {
                    $voteRepository->delete($value);
                }
            }
            $voteRepository->save($vote);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'vote/add.html.twig',
            ['form' => $form->createView(),
                'vote' => $vote,
                'book' => $book, ]
        );
    }
}
