<?php
/**
 * Vote controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\VoteRepository;
use App\Service\VoteService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class VoteController.
 *
 * @Route("/vote")
 */
class VoteController extends AbstractController
{
    /**
     * Book service.
     *
     * @var \App\Service\VoteService
     */
    private $voteService;

    /**
     * BookController constructor.
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
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\VoteRepository            $voteRepository Vote repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="vote_index",
     * )
     */
    public function index(Request $request, VoteRepository $voteRepository, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $voteRepository->queryAll(),
            $request->query->getInt('page', 1),
            VoteRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'vote/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Vote $vote Vote entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="vote_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(Vote $vote): Response
    {
        $voteId = $vote->getId();
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $allBooksInVote = $repository->findBy(['vote' => $voteId]);

        return $this->render(
            'vote/show.html.twig',
            ['vote' => $allBooksInVote]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\VoteRepository            $voteRepository Vote repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/add",
     *     methods={"GET"},
     *     name="vote_add",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function add(UserInterface $loggedUser, Request $request, VoteRepository $voteRepository, string $id): Response
    {
        $vote = new Vote();
        $repository = $this->getDoctrine()->getRepository(Book::class);
        $existingBook = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vote->setUser($loggedUser);
            $vote->setBook($existingBook);

            $voteRepository->save($vote);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('vote_index');
        }

        return $this->render(
            'vote/add.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Entity\Vote                          $vote           Vote entity
     * @param \App\Repository\VoteRepository            $voteRepository Vote repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="vote_edit",
     * )
     */
    public function edit(Request $request, Vote $vote, VoteRepository $voteRepository): Response
    {
        $form = $this->createForm(VoteType::class, $vote, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $voteRepository->save($vote);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('vote_index');
        }

        return $this->render(
            'vote/edit.html.twig',
            [
                'form' => $form->createView(),
                'vote' => $vote,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Vote                          $vote       Vote entity
     * @param \App\Repository\VoteRepository            $repository Vote repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="vote_delete",
     * )
     */
    public function delete(Request $request, Vote $vote, VoteRepository $repository): Response
    {
        if ($vote->getTasks()->count()) {
            $this->addFlash('warning', 'message_vote_contains_tasks');

            return $this->redirectToRoute('vote_index');
        }

        $form = $this->createForm(FormType::class, $vote, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($vote);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('vote_index');
        }

        return $this->render(
            'vote/delete.html.twig',
            [
                'form' => $form->createView(),
                'vote' => $vote,
            ]
        );
    }
}
