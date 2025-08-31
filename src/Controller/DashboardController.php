<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(ChartBuilderInterface $chartBuilder): Response
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }
        $company = $user->getCompany();
        if (!$company) {
            return $this->redirectToRoute('app_home');
        }
        $companyCompleted = $user->getCompany()->isComplete();
        if (!$companyCompleted) {
            return $this->redirectToRoute('app_company_edit', [
                'id' => $company->getId(),
            ]);
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_LINE);
        $chart->setData([
            'labels' => ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ],
            'datasets' => [
                [
                    'label' => 'Chiffre d\'affaires par mois',
                    'data' => [45, 59, 80, 81, 56, 55, 70],
                ],
            ]
        ]);
        $chart->setOptions([
            'scales' => [
                'y' => [
                    'suggestedMin' => 0,
                    'suggestedMax' => 100,
                ],
            ],
            
        ]);
        return $this->render('dashboard/index.html.twig', [
            'controller_name' => 'DashboardController',
            'chart' => $chart,
            'company' => $company,
            'companyCompleted' => $companyCompleted
        ]);
    }

    #[Route('/faq', name: 'app_faq')]
    public function new(): Response
    {
        return $this->render('dashboard/faq.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('dashboard/contact.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
}
