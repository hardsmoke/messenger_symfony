<?php

namespace App\Controller;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/companies")
 */
class CompaniesController extends AbstractController
{
    /**
     * @Route("/", name="app_companies_index", methods={"GET"})
     */
    public function index(CompanyRepository $companyRepository): Response
    {
        return $this->render('companies/index.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_companies_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CompanyRepository $companyRepository): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->add($company, true);

            return $this->redirectToRoute('app_companies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('companies/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/mysubscriptions", name="app_my_subscriptions", methods={"GET", "POST"})
     */
    public function showMySubscriptions(CompanyRepository $companyRepository): Response
    {
        return $this->render('companies/user_companies.html.twig', [
            'companies' => $companyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_companies_show", methods={"GET"})
     */
    public function show(Company $company): Response
    {
        return $this->render('companies/show.html.twig', [
            'company' => $company,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_companies_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->add($company, true);

            return $this->redirectToRoute('app_companies_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('companies/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/subscribe", name="app_companies_subscribe", methods={"GET", "POST"})
     */
    public function subscribe(Company $company, CompanyRepository $companyRepository): Response
    {
        $company->addSubscriber($this->getUser());
        $companyRepository->add($company, true);
        return $this->redirectToRoute('app_companies_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/unsubscribe", name="app_companies_unsubscribe", methods={"GET", "POST"})
     */
    public function unsubscribe(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $company->removeSubscriber($this->getUser());
        $companyRepository->add($company, true);

        $referer = $request->headers->get('referer'); // get the referer, it can be empty!

        $refererPathInfo = Request::create($referer)->getPathInfo();


        $routeInfos = $this->get('router')->match($refererPathInfo);

        $refererRoute = $routeInfos['_route'] ?? '';

        return $this->redirectToRoute($refererRoute, [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="app_companies_delete", methods={"POST"})
     */
    public function delete(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $companyRepository->remove($company, true);
        }

        return $this->redirectToRoute('app_companies_index', [], Response::HTTP_SEE_OTHER);
    }
}
