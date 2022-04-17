<?php

namespace App\Controller;

use App\Entity\Couteau;
use App\Entity\CouteauOutil;
use App\Entity\Outil;
use App\Form\CouteauOutilType;
use App\Form\CouteauType;
use App\Repository\CouteauOutilRepository;
use App\Repository\CouteauRepository;
use App\Repository\OutilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

/**
 * @Route("/couteau")
 */
class CouteauController extends AbstractController
{
    /**
     * @Route("/", name="app_couteau_index", methods={"GET"})
     */
    public function index(CouteauRepository $couteauRepository): Response
    {
        return $this->render('couteau/index.html.twig', [
            'couteaus' => $couteauRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_couteau_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CouteauRepository $couteauRepository): Response
    {
        $couteau = new Couteau();
        $form = $this->createForm(CouteauType::class, $couteau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $couteauRepository->add($couteau);
            return $this->redirectToRoute('app_couteau_show', ['id' => $couteau->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('couteau/new.html.twig', [
            'couteau' => $couteau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_couteau_show", methods={"GET"})
     */
    public function show(Couteau $couteau): Response
    {
        return $this->render('couteau/show.html.twig', [
            'couteau' => $couteau,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_couteau_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Couteau $couteau, CouteauRepository $couteauRepository): Response
    {
        $form = $this->createForm(CouteauType::class, $couteau);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $couteauRepository->add($couteau);
            return $this->redirectToRoute('app_couteau_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('couteau/edit.html.twig', [
            'couteau' => $couteau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_couteau_delete", methods={"POST"})
     */
    public function delete(Request $request, Couteau $couteau, CouteauRepository $couteauRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$couteau->getId(), $request->request->get('_token'))) {
            $couteauRepository->remove($couteau);
        }

        return $this->redirectToRoute('app_couteau_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/addOutil/{id}", name="app_couteau_addOutil", methods={"GET", "POST"})
     */
    public function addOutilsOnCouteau(Request $request, Couteau $couteau, CouteauOutilRepository $couteauOutilRepository, OutilRepository $outilRepository, EntityManagerInterface $em): Response
    {
        $couteauOutil = new CouteauOutil();

        $form = $this->createForm(CouteauOutilType::class, $couteauOutil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $allCouteauOutils = $couteauOutilRepository->findBy(['couteau' => $couteau]);
            $allOutils = [];
            foreach ($allCouteauOutils as $co) {
                $allOutils[] = $co->getOutil();
            }
            /** @var Outil $outil */
            foreach ($form['outil']->getData() as $outil) {
                if (in_array($outil, $allOutils)){
                    $this->addFlash('error', 'Outil déjà choisi pour ce couteau');
                    return $this->renderForm('couteau/addoutil.html.twig', [
                        'couteau' => $couteau,
                        'form' => $form,
                    ]);
                }
                $couteauOutil = new CouteauOutil();
                $couteauOutil->setCouteau($couteau);
                $couteauOutil->setOutil($outil);
                $couteauOutil->setIsDroite(true);
                $couteauOutil->setIsGauche(false);
                $em->persist($couteauOutil);
            }
            $em->flush();

            return $this->redirectToRoute('app_couteau_outil', ['id' => $couteau->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('couteau/addoutil.html.twig', [
            'couteau' => $couteau,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/generateCsvFile/{id}", name="app_couteau_generateCsvFile")
     */
    public function generateCsvFile(Couteau $couteau, CouteauRepository $couteauRepository, CouteauOutilRepository $couteauOutilRepository, OutilRepository $outilRepository):Response
    {
        $data = [["ID OUTILS", "Nom", "activite", "Positionnement", "Taille"]];
        $couteauOutils = $couteauOutilRepository->findBy(['couteau' => $couteau]);
        foreach ($couteauOutils as $couteauOutil) {
            $dataToPush = [];
            if($couteauOutil->getOutil()->getIsCentral()) {
                $position = "M";
            } else if($couteauOutil->getIsDroite()) {
                $position = "D";
            } else {
                $position = "G";
            }

            $dataToPush[] = $couteauOutil->getOutil()->getId();
            $dataToPush[] = $couteauOutil->getOutil()->getNom();
            $dataToPush[] = true;
            $dataToPush[] = $position;
            $dataToPush[] = $couteau->getTaille()->getLibelle();

            $data[] = $dataToPush;
        }

        $fp = fopen('php://temp', 'w');

        foreach ($data as $fields) {
            fputcsv($fp, $fields);
        }

        rewind($fp);
        $response = new Response(stream_get_contents($fp));
        fclose($fp);

        $response->headers->set('Content-Type', 'text/csv');
        //it's gonna output in a testing.csv file
        $response->headers->set('Content-Disposition', 'attachment; filename="CHOIXBDD.csv"');

        return $response;
    }

    /**
     * @Route("/generateInvoice/{id}", name="app_couteau_generateInvoice")
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function generateInvoice(MailerInterface $mailer, Couteau $couteau, CouteauOutilRepository $couteauOutilRepository, Request $request, Pdf $pdf)
    {

        $form = $this->createFormBuilder()
            ->add("sexe", ChoiceType::class, [
                'choices' => array(
                    'm' => 'Homme',
                    'f' => 'Femme',
                ),
                "multiple" => false,
            ])
            ->add("nom", TextType::class)
            ->add("prenom", TextType::class)
            ->add("adresse", TextType::class)
            ->add("cp", TextType::class)
            ->add("ville", TextType::class)
            ->add("mail", EmailType::class)
            ->add("number", TelType::class)
            ->add("nomcb", TextType::class)
            ->add("numcarte", TextType::class)
            ->add("picto", TextType::class)
            ->add("dateExp", DateType::class)
            ->getForm()
        ;
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $couteauOutils = $couteauOutilRepository->findBy(['couteau' => $couteau]);
            $lignesFactures = [];
            $totalMnt = 0;
            foreach ($couteauOutils as $couteauOutil) {
                $lignesFactures[] = ["nom" => $couteauOutil->getOutil()->getNom(), "prix" => $couteauOutil->getOutil()->getPrix()];
                $totalMnt += $couteauOutil->getOutil()->getPrix();
            }

            $html = $this->render("pdf/invoice.html.twig", [
                'couteau' => $couteau,
                'data' => $form->getData(),
                'ligneF' => $lignesFactures,
                'totalMnt' => $totalMnt
            ]);


            $mail = (new Email())
                ->from('noreply@beknife.fr')
                ->to($form->getData()['mail'])
                ->subject('Bravo pour votre achat')
                ->html('<p>Bravo pour votre achat ! Voici la facture en pièce jointe.</p>')
                ->attach($pdf->getOutputFromHtml($html->getContent()), 'facture.pdf')
            ;
            $mailer->send($mail);


            return new PdfResponse($pdf->getOutputFromHtml($html->getContent()),
                'facture_' . bin2hex(random_bytes(10)) . '.pdf',
                'application/pdf',
                'inline'
            );
        }

        return $this->renderForm('couteau/generateInvoice.html.twig', [
            'couteau' => $couteau,
            'form' => $form,
        ]);
    }
}
