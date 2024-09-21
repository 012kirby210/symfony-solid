<?php

namespace App\Controller;

use App\Entity\BigFootSighting;
use App\Form\BigfootSightingType;
use App\Model\DebuggableBigFootSightingScore;
use App\Service\SightingScorer;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BigFootSightingController extends AbstractController
{
    /**
     * @Route("/sighting/upload", name="app_sighting_upload")
     * @IsGranted("ROLE_USER")
     */
    public function upload(Request $request, SightingScorer $sightingScorer, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(BigFootSightingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var BigFootSighting $sighting */
            $sighting = $form->getData();
            $sighting->setOwner($this->getUser());

            $bfsScore = $sightingScorer->score($sighting);
            $sighting->setScore($bfsScore->getScore());

            $entityManager->persist($sighting);
            $entityManager->flush();

            $this->addFlash('success', 'New BigFoot Sighting created successfully!');

            // instanceof : signal d'une cassure du principe de Liskov
            // pour revenir dans le giron de Liskov il faut forcer l'injecteur de dépendance
            // et là, on rentre dans l'horreur fantastique :
            // Pour être intègre par rapport à un principe de développement
            // on couple fortement une partie du runtime (on force le chargement de service)

            // Pourquoi l'intégrité d'un principe devrait tenir sur un choix de contraintes
            // du contexte d'exécution du programme !?

            if ($bfsScore instanceof DebuggableBigFootSightingScore) {
                $this->addFlash('success', sprintf("This scoring took %f ms", $bfsScore->getCalculationTime() * 1000));
            }

            return $this->redirectToRoute('app_sighting_show', [
                'id' => $sighting->getId()
            ]);
        }

        return $this->render('main/sighting_new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/sighting/{id}", name="app_sighting_show")
     */
    public function showSighting(BigFootSighting $bigFootSighting)
    {
        return $this->render('main/sighting_show.html.twig', [
            'sighting' => $bigFootSighting
        ]);
    }
}
