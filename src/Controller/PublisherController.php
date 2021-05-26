<?php
/**
 * Publisher controller.
 */

namespace App\Controller;

use App\Entity\Publisher;
use App\Entity\Book;
use App\Form\PublisherType;
use App\Repository\PublisherRepository;
use App\Service\PublisherService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class PublisherController.
 *
 * @Route("/publisher")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class PublisherController extends AbstractController
{
    /**
     * Publisher service.
     *
     * @var \App\Service\PublisherService
     */
    private $publisherService;

    /**
     * PublisherController constructor.
     *
     * @param \App\Service\PublisherService $publisherService Publisher service
     */
    public function __construct(PublisherService $publisherService)
    {
        $this->publisherService = $publisherService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Repository\PublisherRepository          $publisherRepository Publisher repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator        Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="publisher_index",
     * )
     */
    public function index(Request $request, PublisherRepository $publisherRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->publisherService->createPaginatedList($page);

        return $this->render(
            'publisher/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Repository\PublisherRepository          $publisherRepository Publisher repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="publisher_create",
     * )
     */
    public function create(Request $request, PublisherRepository $publisherRepository): Response
    {
        $publisher = new Publisher();
        $form = $this->createForm(PublisherType::class, $publisher);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherRepository->save($publisher);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render(
            'publisher/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Entity\Publisher                        $publisher           Publisher entity
     * @param \App\Repository\PublisherRepository          $publisherRepository Publisher repository
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
     *     name="publisher_edit",
     * )
     */
    public function edit(Request $request, Publisher $publisher, PublisherRepository $publisherRepository): Response
    {
        $form = $this->createForm(PublisherType::class, $publisher, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $publisherRepository->save($publisher);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render(
            'publisher/edit.html.twig',
            [
                'form' => $form->createView(),
                'publisher' => $publisher,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Publisher                        $publisher     Publisher entity
     * @param \App\Repository\PublisherRepository          $repository Publisher repository
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
     *     name="publisher_delete",
     * )
     */
    public function delete(Request $request, Publisher $publisher, PublisherRepository $repository): Response
    {
        $publisherId = $publisher->getId();
        $repositoryBook = $this->getDoctrine()->getRepository(Book::class);
        $existingBook = $repositoryBook->findOneBy(['publisher' => $publisherId]);

        if ($existingBook) {
            $this->addFlash('warning', 'message_publisher_contains_objects');

            return $this->redirectToRoute('publisher_index');
        }

        $form = $this->createForm(FormType::class, $publisher, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($publisher);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('publisher_index');
        }

        return $this->render(
            'publisher/delete.html.twig',
            [
                'form' => $form->createView(),
                'publisher' => $publisher,
            ]
        );
    }
}
