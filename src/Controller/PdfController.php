<?php

namespace App\Controller;

use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Snappy\Pdf;

class PdfController extends AbstractController
{
    #[Route('/mipdf', name: 'mipdf')]
    public function pdfAction(Pdf $knpSnappyPdf)
    {
        $html = $this->renderView('pdf/index.html.twig');

        return new PdfResponse(
            $knpSnappyPdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}