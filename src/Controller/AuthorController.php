<?php
/**
 * Author controller.
 */

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use App\Service\AuthorService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AuthorController.
 *
 * @Route("/author")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class AuthorController extends AbstractController
{
    /**
     * Author service.
     *
     * @var \App\Service\AuthorService
     */
    private $authorService;

    /**
     * AuthorController constructor.
     *
     * @param \App\Service\AuthorService $authorService Author service
     */
    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request HTTP petition
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="author_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->authorService->createPaginatedList($page);

        return $this->render(
            'author/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Repository\AuthorRepository          $authorRepository Author repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="author_create",
     * )
     */
    public function create(Request $request, AuthorRepository $authorRepository): Response
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorRepository->save($author);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Entity\Author                        $author           Author entity
     * @param \App\Repository\AuthorRepository          $authorRepository Author repository
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
     *     name="author_edit",
     * )
     */
    public function edit(Request $request, Author $author, AuthorRepository $authorRepository): Response
    {
        $form = $this->createForm(AuthorType::class, $author, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $authorRepository->save($author);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/edit.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Author                        $author     Author entity
     * @param \App\Repository\AuthorRepository          $repository Author repository
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
     *     name="author_delete",
     * )
     */
    public function delete(Request $request, Author $author, AuthorRepository $repository): Response
    {
        $existingBook = $author->getBooks();

        if (0 != count($existingBook)) {
            $this->addFlash('warning', 'message_author_contains_objects');

            return $this->redirectToRoute('author_index');
        }

        $form = $this->createForm(FormType::class, $author, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($author);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('author_index');
        }

        return $this->render(
            'author/delete.html.twig',
            [
                'form' => $form->createView(),
                'author' => $author,
            ]
        );
    }
}
