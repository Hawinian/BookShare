<?php
/**
 * Status controller.
 */

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Book;
use App\Form\StatusType;
use App\Repository\StatusRepository;
use App\Service\StatusService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class StatusController.
 *
 * @Route("/status")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class StatusController extends AbstractController
{
    /**
     * Status service.
     *
     * @var \App\Service\StatusService
     */
    private $statusService;

    /**
     * StatusController constructor.
     *
     * @param \App\Service\StatusService $statusService Status service
     */
    public function __construct(StatusService $statusService)
    {
        $this->statusService = $statusService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="status_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->statusService->createPaginatedList($page);

        return $this->render(
            'status/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Repository\StatusRepository          $statusRepository Status repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="status_create",
     * )
     */
    public function create(Request $request, StatusRepository $statusRepository): Response
    {
        $status = new Status();
        $form = $this->createForm(StatusType::class, $status);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statusRepository->save($status);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('status_index');
        }

        return $this->render(
            'status/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Entity\Status                        $status           Status entity
     * @param \App\Repository\StatusRepository          $statusRepository Status repository
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
     *     name="status_edit",
     * )
     */
    public function edit(Request $request, Status $status, StatusRepository $statusRepository): Response
    {
        $form = $this->createForm(StatusType::class, $status, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $statusRepository->save($status);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('status_index');
        }

        return $this->render(
            'status/edit.html.twig',
            [
                'form' => $form->createView(),
                'status' => $status,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Status                        $status     Status entity
     * @param \App\Repository\StatusRepository          $repository Status repository
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
     *     name="status_delete",
     * )
     */
    public function delete(Request $request, Status $status, StatusRepository $repository): Response
    {
        $existingBook = $status->getBooks();

        if (0 != count($existingBook)) {
            $this->addFlash('warning', 'message_status_contains_objects');

            return $this->redirectToRoute('status_index');
        }

        $form = $this->createForm(FormType::class, $status, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($status);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('status_index');
        }

        return $this->render(
            'status/delete.html.twig',
            [
                'form' => $form->createView(),
                'status' => $status,
            ]
        );
    }
}
