<?php

namespace App\Controller;

use App\Service\StatsService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     *
     * @param StatsService $statsService
     *
     * @return Response
     */
    public function index(StatsService $statsService)
    {
        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $statsService->getStats(),
            'bestAds' => $statsService->getAdsStats('DESC'),
            'worstAds' => $statsService->getAdsStats('ASC')
        ]);
    }
}
