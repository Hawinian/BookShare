<?php
/**
 * Book controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Rental;
use App\Entity\Vote;
use App\Form\BookType;
use App\Form\SearchType;
use App\Form\VoteType;
use App\Repository\BookRepository;
use App\Repository\VoteRepository;
use App\Service\BookService;
use App\Service\FileUploader;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class BookController.
 */
class BookController extends AbstractController
{
    /**
     * Book service.
     *
     * @var \App\Service\BookService
     */
    private $bookService;

    /**
     * User service.
     *
     * @var \App\Service\UserService
     */
    private $userService;

    /**
     * File uploader.
     *
     * @var \App\Service\FileUploader
     */
    private $fileUploader;

    /**
     * Filesystem component.
     *
     * @var \Symfony\Component\Filesystem\Filesystem
     */
    private $filesystem;

    /**
     * BookController constructor.
     *
     * @param \App\Service\BookService                 $bookService  Book service
     * @param \App\Service\FileUploader                $fileUploader File uploader
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem   Filesystem component
     */
    public function __construct(BookService $bookService, UserService $userService, FileUploader $fileUploader, Filesystem $filesystem)
    {
        $this->bookService = $bookService;
        $this->userService = $userService;
        $this->fileUploader = $fileUploader;
        $this->filesystem = $filesystem;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\BookRepository            $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="book_index",
     * )
     */
    public function index(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator, BookService $bookService): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');
        $filters['language_id'] = $request->query->getInt('filters_language_id');
        $filters['author_id'] = $request->query->getInt('filters_author_id');

        $pagination = $this->bookService->createPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render(
            'book/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Show action.
     *
     * @param \App\Entity\Book $book Book entity
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     methods={"GET"},
     *     name="book_show",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function show(TokenStorageInterface $tokenStorage, Book $book): Response
    {
        //UserInterface $loggedUser,
        $user = $tokenStorage->getToken() ? $tokenStorage->getToken()->getUser() : null;
        if ($user != 'anon.') {
            $bookId = $book->getId();
            dump($user);
            $userId = $user->getId();
            $repository = $this->getDoctrine()->getRepository(Vote::class);
            $existingRate = $repository->findOneBy(['book' => $bookId, 'user' => $userId]);

            if ($existingRate) {
                return $this->render(
                    'book/show.html.twig',
                    ['book' => $book, 'rate' => $existingRate]
                );
            }
        }

        return $this->render(
            'book/show.html.twig',
            ['book' => $book, 'rate' => null]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\BookRepository            $bookRepository Book repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="book_create",
     * )
     */
    public function create(Request $request, BookRepository $bookRepository): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFilename = $this->fileUploader->upload(
                $form->get('image')->getData()
            );
            $book->setImage($imageFilename);
            $bookRepository->save($book);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Entity\Book                          $book           Book entity
     * @param \App\Repository\BookRepository            $bookRepository Book repository
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
     *     name="book_edit",
     * )
     */
    public function edit(Request $request, Book $book, BookRepository $bookRepository): Response
    {
        $form = $this->createForm(BookType::class, $book, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->filesystem->remove(
                $this->getParameter('images_directory').'/'.$book->getImage()
            );
            $imageFilename = $this->fileUploader->upload(
                $form->get('image')->getData()
            );
            $book->setImage($imageFilename);
            $bookRepository->save($book);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/edit.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Book                          $book       Book entity
     * @param \App\Repository\BookRepository            $repository Book repository
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
     *     name="book_delete",
     * )
     */
    public function delete(Request $request, Book $book, BookRepository $repository): Response
    {
        $bookId = $book->getId();
        $repositoryRental = $this->getDoctrine()->getRepository(Rental::class);
        $existingRental = $repositoryRental->findOneBy(['book' => $bookId]);
        $repositoryPetition = $this->getDoctrine()->getRepository(Rental::class);
        $existingPetition = $repositoryPetition->findOneBy(['book' => $bookId]);

        if ($existingRental or $existingPetition) {
            $this->addFlash('warning', 'message_book_contains_objects');

            return $this->redirectToRoute('book_index');
        }

        $form = $this->createForm(FormType::class, $book, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($book);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/delete.html.twig',
            [
                'form' => $form->createView(),
                'book' => $book,
            ]
        );
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
     *     "/search",
     *     methods={"GET", "POST"},
     *     name="book_search",
     * )
     */
    public function search(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator, BookService $bookService): Response
    {
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repository = $this->getDoctrine()->getRepository(Book::class);
            $title = $form->getData();
            $title = array_shift($title);
            $existingBook = $repository->findOneBy(['title' => $title]);

            if ($existingBook) {
                $book_id = $existingBook->getId();

                return $this->redirectToRoute('book_show', ['id' => $book_id]);
            } else {
                $this->addFlash('success', 'cannot find this book');

                return $this->redirectToRoute('book_search');
            }
        }

        return $this->render(
            'book/search.html.twig',
            ['form' => $form->createView()]
        );
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
    public function vote(UserInterface $loggedUser, Request $request, VoteRepository $voteRepository, Book $book, BookRepository $bookRepository): Response
    {
        $vote = new Vote();
        $vote->setBook($book);
        $vote->setRate(0);
        $vote->setUser($loggedUser);

        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$vote->setRate('rate');
            $userId = $loggedUser->getId();
            $repository = $this->getDoctrine()->getRepository(Vote::class);
            $allrates = $repository->findBy(['user' => $userId]);
            foreach ($allrates as &$value) {
                $voteRepository->delete($value);
            }
            $voteRepository->save($vote);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'book/vote.html.twig',
            ['form' => $form->createView(),
            'vote' => $vote, ]
        );
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\BookRepository            $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/ranking",
     *     methods={"GET", "POST"},
     *     name="book_ranking",
     * )
     */
    public function ranking(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator, BookService $bookService): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');
        $filters['tag_id'] = $request->query->getInt('filters_tag_id');
        $filters['language_id'] = $request->query->getInt('filters_language_id');
        $pagination = $this->bookService->createPaginatedListForRanking(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render(
            'book/ranking.html.twig',
            ['pagination' => $pagination]
        );
    }

//    /**
//     * Index action.
//     *
//     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP search
//     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
//     *
//     * @return \Symfony\Component\HttpFoundation\Response HTTP response
//     *
//     * @Route(
//     *     "/{id}/vote",
//     *     methods={"GET", "POST"},
//     *     requirements={"id": "[1-9]\d*"},
//     *     name="edit_vote",
//     * )
//     */
//    public function edit_vote(UserInterface $loggedUser, Request $request, VoteRepository $voteRepository, Vote $vote, BookRepository $bookRepository): Response
//    {
//
//        $form = $this->createForm(VoteType::class, $vote, ['method' => 'PUT']);
//        $form->handleRequest($request);
//
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            #$vote->setRate('rate');
//            $voteRepository->save($vote);
//
//            $this->addFlash('success', 'message_created_successfully');
//
//            return $this->redirectToRoute('book_index');
//        }
//
//        return $this->render(
//            'book/vote.html.twig',
//            ['form' => $form->createView(),
//                'vote' => $vote, ]
//        );
//    }
}
