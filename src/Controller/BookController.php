<?php
/**
 * Book controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Category;
use App\Entity\Vote;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Service\BookService;
use App\Service\FileUploader;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param \App\Service\BookService $bookService Book service
     * @param \App\Service\FileUploader        $fileUploader     File uploader
     * @param \Symfony\Component\Filesystem\Filesystem $filesystem       Filesystem component
     */
    public function __construct(BookService $bookService, FileUploader $fileUploader, Filesystem $filesystem)
    {
        $this->bookService = $bookService;
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
        #$filters['vote_average'] = $request->query->getInt('filters_vote_average');

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
     *  action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\BookRepository            $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/category/{id}",
     *     methods={"GET"},
     *     name="book_category",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function showCategories(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator, Category $category, string $id): Response
    {
        $pagination = $paginator->paginate(
            $bookRepository->findByCategory($id),
            $request->query->getInt('page', 1),
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
        );

        return $this->render(
            'book/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     *  action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request        HTTP petition
     * @param \App\Repository\BookRepository            $bookRepository Book repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator      Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/tag/{id}",
     *     methods={"GET"},
     *     name="book_tag",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function showTags(Request $request, BookRepository $bookRepository, PaginatorInterface $paginator, string $id): Response
    {
        $pagination = $paginator->paginate(
            $bookRepository->findByTag($id),
            $request->query->getInt('page', 1),
            BookRepository::PAGINATOR_ITEMS_PER_PAGE
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
    public function show(Book $book): Response
    {
        return $this->render(
            'book/show.html.twig',
            [   'book' => $book]
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
        /*
        if ($book->getTasks()->count()) {
            $this->addFlash('warning', 'message_book_contains_tasks');

            return $this->redirectToRoute('book_index');
        }
        */

        $form = $this->createForm(BookType::class, $book, ['method' => 'DELETE']);
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
}
