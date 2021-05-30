<?php
/**
 * Petition controller.
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Petition;
use App\Entity\Rental;
use App\Form\PetitionType;
use App\Repository\PetitionRepository;
use App\Repository\RentalRepository;
use App\Service\PetitionService;
use DateInterval;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PetitionController.
 *
 * @Route("/petition")
 *
 * @IsGranted("ROLE_USER")
 */
class PetitionController extends AbstractController
{
    /**
     * Petition service.
     *
     * @var \App\Service\PetitionService
     */
    private $petitionService;

    /**
     * PetitionController constructor.
     *
     * @param \App\Service\PetitionService $petitionService Petition service
     */
    public function __construct(PetitionService $petitionService)
    {
        $this->petitionService = $petitionService;
    }

    /**
     * Index action.
     *
     * @IsGranted("ROLE_ADMIN")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP petition
     * @param \App\Repository\PetitionRepository        $petitionRepository Petition repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator          Paginator
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "",
     *     methods={"GET"},
     *     name="petition_index",
     * )
     */
    public function index(Request $request, PetitionRepository $petitionRepository, PaginatorInterface $paginator): Response
    {
        $page = $request->query->getInt('page', 1);
        $pagination = $this->petitionService->createPaginatedList($page);

        return $this->render(
            'petition/index.html.twig',
            ['pagination' => $pagination]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request            HTTP petition
     * @param \App\Repository\PetitionRepository        $petitionRepository Petition repository
     * @param int                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/create",
     *     methods={"GET", "POST"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="petition_create",
     * )
     */
    public function create(Request $request, Book $book, PetitionRepository $requestRepository, string $id): Response
    {
        $petiton = new Petition();
        $form = $this->createForm(PetitionType::class, $petiton);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $petiton->setUser($this->getUser());
            $petiton->setBook($book);
            $petiton->setDate(new \DateTime());
            $requestRepository->save($petiton);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('book_index');
        }

        return $this->render(
            'petition/create.html.twig',
            ['book' => $book,
                'form' => $form->createView(), ]
        );
    }

    /**
     * Create action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP rental
     * @param \App\Repository\RentalRepository          $rentalRepository Rental repository
     * @param int                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/accept",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="petition_accept",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function accept(Request $request, Petition $petition, RentalRepository $rentalRepository, PetitionRepository $petitionRepository, string $id): Response
    {
        $existingRental = $petition->getBook()->getRentals();

        if (0 != count($existingRental)) {
            $this->addFlash('warning', 'message_book_already_rented');

            return $this->redirectToRoute('petition_index');
        }

        $form = $this->createForm(FormType::class, $petition, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rental = new Rental();
            $rental->setDateOfRental((new \DateTime()));
            $date = new \DateTime(); // Y-m-d
            $date->add(new DateInterval('P30D'));
            $rental->setDateOfReturn($date);
            $rental->setUser($petition->getUser());
            $rental->setBook($petition->getBook());
            $rentalRepository->save($rental);
            $petitionRepository->delete($petition);

            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('petition_index');
        }

        return $this->render(
            'petition/accept.html.twig',
            [
                'form' => $form->createView(),
                'petition' => $petition,
            ]
        );
    }

    /**
     * Reject action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request          HTTP rental
     * @param \App\Repository\RentalRepository          $rentalRepository Rental repository
     * @param int                                       $id
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/reject",
     *     methods={"GET","DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="petition_reject",
     * )
     *
     * @IsGranted("ROLE_ADMIN")
     */
    public function reject(Request $request, Petition $petition, PetitionRepository $petitionRepository, string $id): Response
    {
        $form = $this->createForm(FormType::class, $petition, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $petitionRepository->delete($petition);
            $this->addFlash('success', 'message_deleted_successfully');

            return $this->redirectToRoute('petition_index');
        }

        return $this->render(
            'petition/reject.html.twig',
            [
                'form' => $form->createView(),
                'petition' => $petition,
            ]
        );
    }
}
