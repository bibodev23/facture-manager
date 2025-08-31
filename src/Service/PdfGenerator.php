<?php

namespace App\Service;

use Nucleos\DompdfBundle\Factory\DompdfFactory;

class PdfGenerator
{
    public function __construct(
        private readonly DompdfFactory $factory
        ) {
    }

    public function generatePdf(string $htmlContent): string
    {
        $pdf = $this->factory->create();

        $pdf->loadHtml($htmlContent);
        $pdf->setPaper('A4', 'portrait');
        $pdf->render();

        return $pdf->output();
    }
}