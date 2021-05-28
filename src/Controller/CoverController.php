<?php
/**
 * Cover controller.
 */

namespace App\Controller;

use App\Entity\Cover;
use App\Form\CoverType;
use App\Repository\CoverRepository;
use App\Service\CoverService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CoverController.
 *
 * @Route("/cover")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class CoverController extends AbstractController
{
    /**
     * Cover service.
     *
     * @var \App\Service\CoverService
     */
    private $coverService;

    /**
     * CoverController constructor.
     *
     * @param \App\Service\CoverService $coverService Cover service
     */
    public function __construct(CoverService $coverService)
    {
        $this->coverService = $coverService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP petition
     * @param \App\Repository\CoverRepository           $coverRepository Cover repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator       Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="cover_index",
     * )
     */
    public function index(Request $request, CoverRepository $coverRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->coverService->createPaginatedList($page);

        return $this->render(
            'cover/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP petition
     * @param \App\Repository\CoverRepository           $coverRepository Cover repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="cover_create",
     * )
     */
    public function create(Request $request, CoverRepository $coverRepository): Response
    {
        $cover = new Cover();
        $form = $this->createForm(CoverType::class, $cover);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverRepository->save($cover);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('cover_index');
        }

        return $this->render(
            'cover/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request         HTTP petition
     * @param \App\Entity\Cover                         $cover           Cover entity
     * @param \App\Repository\CoverRepository           $coverRepository Cover repository
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
     *     name="cover_edit",
     * )
     */
    public function edit(Request $request, Cover $cover, CoverRepository $coverRepository): Response
    {
        $form = $this->createForm(CoverType::class, $cover, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coverRepository->save($cover);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('cover_index');
        }

        return $this->render(
            'cover/edit.html.twig',
            [
                'form' => $form->createView(),
                'cover' => $cover,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Cover                         $cover      Cover entity
     * @param \App\Repository\CoverRepository           $repository Cover repository
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
     *     name="cover_delete",
     * )
     */
    public function delete(Request $request, Cover $cover, CoverRepository $repository): Response
    {
        $existingBook = $cover->getBooks();

        if (0 != count($existingBook)) {
            $this->addFlash('warning', 'message_cover_contains_objects');

            return $this->redirectToRoute('cover_index');
        }

        $form = $this->createForm(FormType::class, $cover, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($cover);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('cover_index');
        }

        return $this->render(
            'cover/delete.html.twig',
            [
                'form' => $form->createView(),
                'cover' => $cover,
            ]
        );
    }
}
