<?php
// src/Service/PdfService.php

namespace App\Service;

use Dompdf\Dompdf;
use Dompdf\Options;
use Twig\Environment;

class PdfService
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function generatePdfFromTemplate(string $templatePath, array $parameters): string
    {
        // Generar el HTML a partir de la plantilla Twig
        $html = $this->twig->render($templatePath, $parameters);

        // Configurar opciones de Dompdf
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);

        // Crear instancia de Dompdf
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');

        // Renderizar el PDF
        $dompdf->render();

        // Devolver el contenido del PDF como una cadena de bytes
        return $dompdf->output();
    }
}
