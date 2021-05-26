<?php
/**
 * PetitionKind controller.
 */

namespace App\Controller;

use App\Entity\PetitionKind;
use App\Entity\Book;
use App\Form\PetitionKindType;
use App\Repository\PetitionKindRepository;
use App\Service\PetitionKindService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Class PetitionKindController.
 *
 * @Route("/petition_kind")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class PetitionKindController extends AbstractController
{
    /**
     * PetitionKind service.
     *
     * @var \App\Service\PetitionKindService
     */
    private $petition_kindService;

    /**
     * PetitionKindController constructor.
     *
     * @param \App\Service\PetitionKindService $petition_kindService PetitionKind service
     */
    public function __construct(PetitionKindService $petition_kindService)
    {
        $this->petition_kindService = $petition_kindService;
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
     *     name="petition_kind_index",
     * )
     */
    public function index(Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->petition_kindService->createPaginatedList($page);

        return $this->render(
            'petition_kind/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Repository\PetitionKindRepository          $petition_kindRepository PetitionKind repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="petition_kind_create",
     * )
     */
    public function create(Request $request, PetitionKindRepository $petition_kindRepository): Response
    {
        $petition_kind = new PetitionKind();
        $form = $this->createForm(PetitionKindType::class, $petition_kind);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $petition_kindRepository->save($petition_kind);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('petition_kind_index');
        }

        return $this->render(
            'petition_kind/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP petition
     * @param \App\Entity\PetitionKind                        $petition_kind           PetitionKind entity
     * @param \App\Repository\PetitionKindRepository          $petition_kindRepository PetitionKind repository
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
     *     name="petition_kind_edit",
     * )
     */
    public function edit(Request $request, PetitionKind $petition_kind, PetitionKindRepository $petition_kindRepository): Response
    {
        $form = $this->createForm(PetitionKindType::class, $petition_kind, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $petition_kindRepository->save($petition_kind);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('petition_kind_index');
        }

        return $this->render(
            'petition_kind/edit.html.twig',
            [
                'form' => $form->createView(),
                'petition_kind' => $petition_kind,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\PetitionKind                        $petition_kind     PetitionKind entity
     * @param \App\Repository\PetitionKindRepository          $repository PetitionKind repository
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
     *     name="petition_kind_delete",
     * )
     */
    public function delete(Request $request, PetitionKind $petition_kind, PetitionKindRepository $repository): Response
    {
        $existingBook = $petition_kind->getBooks();

        if (0 != count($existingBook)) {
            $this->addFlash('warning', 'message_petition_kind_contains_objects');

            return $this->redirectToRoute('petition_kind_index');
        }

        $form = $this->createForm(FormType::class, $petition_kind, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($petition_kind);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('petition_kind_index');
        }

        return $this->render(
            'petition_kind/delete.html.twig',
            [
                'form' => $form->createView(),
                'petition_kind' => $petition_kind,
            ]
        );
    }
}
