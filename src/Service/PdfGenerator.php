<?php

namespace App\Service;

use Nucleos\DompdfBundle\Factory\DompdfFactory;


class PdfGenerator
{
    public function __construct(
        private readonly DompdfFactory $factory,
        ) {
    }

    public function generatePdf(string $htmlContent): string
    {
        $pdf = $this->factory->create();
        $options = new \Dompdf\Options();
        $options
            ->set('defaultFont', 'Arial')
            ->set('isRemoteEnabled', true)
            ->set('isHtml5ParserEnabled', true)
            ->set('defaultFont', 'DejaVu Sans')
            ->set('dpi', 150);
        $pdf->setOptions($options);

        $pdf->loadHtml($htmlContent);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->output();
    }
}