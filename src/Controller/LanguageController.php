<?php
/**
 * Language controller.
 */

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Repository\LanguageRepository;
use App\Service\LanguageService;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class LanguageController.
 *
 * @Route("/language")
 *
 * @IsGranted("ROLE_ADMIN")
 */
class LanguageController extends AbstractController
{
    /**
     * Language service.
     *
     * @var \App\Service\LanguageService
     */
    private $languageService;

    /**
     * LanguageController constructor.
     *
     * @param \App\Service\LanguageService $languageService Language service
     */
    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    /**
     * Index action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP petition
     * @param \App\Repository\LanguageRepository        $languageRepository Language repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="language_index",
     * )
     */
    public function index(Request $request, LanguageRepository $languageRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->languageService->createPaginatedList($page);

        return $this->render(
            'language/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP petition
     * @param \App\Repository\LanguageRepository        $languageRepository Language repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/create",
     *     methods={"GET", "POST"},
     *     name="language_create",
     * )
     */
    public function create(Request $request, LanguageRepository $languageRepository): Response
    {
        $language = new Language();
        $form = $this->createForm(LanguageType::class, $language);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $languageRepository->save($language);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('language_index');
        }

        return $this->render(
            'language/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP petition
     * @param \App\Entity\Language                      $language           Language entity
     * @param \App\Repository\LanguageRepository        $languageRepository Language repository
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
     *     name="language_edit",
     * )
     */
    public function edit(Request $request, Language $language, LanguageRepository $languageRepository): Response
    {
        $form = $this->createForm(LanguageType::class, $language, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $languageRepository->save($language);

            $this->addFlash('success', 'message_updated_successfully');

            return $this->redirectToRoute('language_index');
        }

        return $this->render(
            'language/edit.html.twig',
            [
                'form' => $form->createView(),
                'language' => $language,
            ]
        );
    }

    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP petition
     * @param \App\Entity\Language                      $language   Language entity
     * @param \App\Repository\LanguageRepository        $repository Language repository
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
     *     name="language_delete",
     * )
     */
    public function delete(Request $request, Language $language, LanguageRepository $repository): Response
    {
        $existingBook = $language->getBooks();

        if (0 != count($existingBook)) {
            $this->addFlash('warning', 'message_language_contains_objects');

            return $this->redirectToRoute('language_index');
        }

        $form = $this->createForm(FormType::class, $language, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($language);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('language_index');
        }

        return $this->render(
            'language/delete.html.twig',
            [
                'form' => $form->createView(),
                'language' => $language,
            ]
        );
    }
}
