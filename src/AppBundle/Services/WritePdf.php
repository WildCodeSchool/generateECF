<?php

namespace AppBundle\Services;

use AppBundle\Entity\Promo;
use AppBundle\Entity\Student;
use setasign\Fpdi\Fpdi;

/**
 * Class WritePdf
 * @package AppBundle\Services
 */
class WritePdf
{
    /**
     * @var
     */
    private $template_directory;
    /**
     * @var
     */
    private $output;

    /**
     * WritePdf constructor.
     * @param $template_directory
     * @param $output
     */
    public function __construct($template_directory, $output)
    {
        $this->template_directory = $template_directory;
        $this->output = $output;
    }

    /**
     * @param Student $student
     * @return string
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function generatePdf(Student $student, Promo $promo){
        // initiate FPDI
        $pdf = new Fpdi();
        // set the source file
        $pdf->setSourceFile($this->template_directory . "template.pdf");

        // Generate page 1

        // import page 1
        $tplIdx = $pdf->importPage(1);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setSimpleTxt($pdf, utf8_decode($student->getPromo()->getCity()->getName()), 75.5, 176);
        $this->setSimpleTxt($pdf, utf8_decode($student->getName()), 75.5, 194);
        $this->setSimpleTxt($pdf, utf8_decode($student->getFirstname()), 75.5, 200);
        $this->setSimpleTxt($pdf, $student->getDateOfBirth()->format('d-m-Y'), 75.5, 206);

        if ($student->getGender() != 0){
            $this->setValidation($pdf, utf8_decode($student->getGender()), 90, 188.8, 110, 188.8);
        }

        // Page 2
        $tplIdx = $pdf->importPage(2);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

        // Page 3
        $tplIdx = $pdf->importPage(3);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

        // import page 4
        $tplIdx = $pdf->importPage(4);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityOne(), 16, 217, 16, 224);

//         import page 5
        $tplIdx = $pdf->importPage(5);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setLongText($pdf, utf8_decode($student->getCommActivityOne()), 19, 62);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 225);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 225);


//         import page 6
        $tplIdx = $pdf->importPage(6);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateEvalSuppOne(), 16, 145, 16, 156);
        $this->setLongText($pdf, utf8_decode($student->getCommEvalSuppOne()), 19, 179);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 240);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 240);

//        Import page 7
        $tplIdx = $pdf->importPage(7);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

// import page 8
        $tplIdx = $pdf->importPage(8);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityTwo(), 16, 68, 16, 75);
        $this->setLongText($pdf, utf8_decode($student->getCommActivityTwo()), 19, 100);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 187);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 187);

        // import page 9
        $tplIdx = $pdf->importPage(9);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateEvalSuppTwo(), 16, 151, 16, 161);
        $this->setLongText($pdf, utf8_decode($student->getCommEvalSuppTwo()), 19, 182);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 246);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 246);

        // import page 10
        $tplIdx = $pdf->importPage(10);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityOne(), 78, 182, 94, 145);
        $this->setValidation($pdf, $student->getValidateActivityTwo(), 78, 145, 94, 182);

//         import page 11
        $tplIdx = $pdf->importPage(11);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setLongText($pdf, utf8_decode($student->getObservationStudent()), 19, 72);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 154);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 154);

        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 177);

        $filename = $student->getName() . '_' . $student->getFirstname() . '_ecf.pdf';
        if (!file_exists($this->output . str_replace(' ', '_', $student->getPromo()->getName()))) {
            mkdir($this->output . str_replace(' ', '_', $student->getPromo()->getName()), 0777, true);
        }

        $name = $this->output . str_replace(' ', '_', $student->getPromo()->getName()) . '/' . $filename;
        $pdf->Output('F', $name);
        return $name;
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    private function setSimpleTxt(Fpdi $pdf, $value, $x, $y){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY($x, $y);
        $pdf->Write(0, $value);
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    private function setValidation(Fpdi $pdf, $value, $xTrue, $yTrue, $xFalse, $yFalse){
        if ($value == true){
            $pdf->SetXY($xTrue, $yTrue);
        } elseif ($value == false){
            $pdf->SetXY($xFalse, $yFalse);
        }
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(0, 0, '4', 0, 0);
    }

    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    private function setLongText(Fpdi $pdf, $comm, $x, $y){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY($x, $y);

        // Sortie du texte justifié
        $pdf->MultiCell(170,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }
}
