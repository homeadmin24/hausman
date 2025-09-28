<?php

namespace App\Controller;

use App\Entity\WegEinheit;
use App\Repository\DienstleisterRepository;
use App\Repository\KostenkontoRepository;
use App\Repository\ZahlungRepository;
use App\Repository\ZahlungskategorieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/zahlung')]
class ZahlungController extends AbstractController
{
    #[Route('/', name: 'app_zahlung_index', methods: ['GET'])]
    public function index(Request $request, ZahlungRepository $zahlungRepository, KostenkontoRepository $kostenkontoRepository, ZahlungskategorieRepository $zahlungskategorieRepository, DienstleisterRepository $dienstleisterRepository, EntityManagerInterface $entityManager): Response
    {
        // Get filter parameters
        $kostenkontoId = $request->query->get('kostenkonto');
        $zahlungskategorieId = $request->query->get('zahlungskategorie');
        $dienstleisterId = $request->query->get('dienstleister');
        $wegEinheitId = $request->query->get('weg_einheit');

        // Build criteria for filtering
        $criteria = [];
        if ($kostenkontoId) {
            $criteria['kostenkonto'] = $kostenkontoId;
        }
        if ($zahlungskategorieId) {
            $criteria['hauptkategorie'] = $zahlungskategorieId;
        }
        if ($dienstleisterId) {
            $criteria['dienstleister'] = $dienstleisterId;
        }
        if ($wegEinheitId) {
            $criteria['eigentuemer'] = $wegEinheitId;
        }

        // Get filtered payments
        $zahlungen = $zahlungRepository->findBy(
            $criteria,
            ['datum' => 'DESC']
        );

        // Get all data for filter dropdowns
        $kostenkontos = $kostenkontoRepository->findBy(['isActive' => true], ['nummer' => 'ASC']);
        $zahlungskategorien = $zahlungskategorieRepository->findBy([], ['name' => 'ASC']);
        $dienstleister = $dienstleisterRepository->findServiceProvidersOnly();
        $wegEinheiten = $entityManager->getRepository(WegEinheit::class)->findBy([], ['nummer' => 'ASC']);

        return $this->render('zahlung/index.html.twig', [
            'zahlungen' => $zahlungen,
            'kostenkontos' => $kostenkontos,
            'zahlungskategorien' => $zahlungskategorien,
            'dienstleister' => $dienstleister,
            'wegEinheiten' => $wegEinheiten,
            'selectedKostenkonto' => $kostenkontoId,
            'selectedZahlungskategorie' => $zahlungskategorieId,
            'selectedDienstleister' => $dienstleisterId,
            'selectedWegEinheit' => $wegEinheitId,
        ]);
    }
}
