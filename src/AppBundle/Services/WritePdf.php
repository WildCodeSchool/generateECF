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
     * @var
     */
    private $signDirectory;

    /**
     * WritePdf constructor.
     * @param $template_directory
     * @param $output
     */
    public function __construct($template_directory, $output, $signDirectory)
    {
        $this->template_directory = $template_directory;
        $this->output = $output;
        $this->signDirectory = $signDirectory;
    }

    /**
     * @param Student $student
     * @return string
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function generatePdfOldVersion(Student $student, Promo $promo){
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
        $this->setSimpleTxt($pdf, utf8_decode($promo->getAdress()), 75.5, 177);
        $this->setSimpleTxt($pdf, utf8_decode($student->getName()), 75.5, 195);
        $this->setSimpleTxt($pdf, utf8_decode($student->getFirstname()), 75.5, 201);
        $this->setSimpleTxt($pdf, $student->getDateOfBirth()->format('d-m-Y'), 75.5, 207);

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
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 226);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 226);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 216,25);

//         import page 6
        $tplIdx = $pdf->importPage(6);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateEvalSuppOne(), 16, 145, 16, 156);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 240);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 240);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 230,25);

//        Import page 7
        $tplIdx = $pdf->importPage(7);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

// import page 8
        $tplIdx = $pdf->importPage(8);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityTwo(), 16, 69, 16, 76);
        $this->setLongText($pdf, utf8_decode($student->getCommActivityTwo()), 19, 100);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 188);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 188);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 180,25);


        // import page 9
        $tplIdx = $pdf->importPage(9);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateEvalSuppTwo(), 16, 152, 16, 162);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 247);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 247);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 237,25);

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
        $this->setSimpleTxt($pdf, utf8_decode($promo->getCampusManager()), 37, 177.5);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 177);

        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 158, 146,25);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignCM(), 158, 169,25);

        $filename = $student->getName() . '_' . $student->getFirstname() . '_ecf.pdf';
        if (!file_exists($this->output . str_replace(' ', '_', $student->getPromo()->getName()))) {
            mkdir($this->output . str_replace(' ', '_', $student->getPromo()->getName()), 0777, true);
        }

        $name = $this->output . str_replace(' ', '_', $student->getPromo()->getName()) . '/' . $filename;
        $pdf->Output('F', $name);
        return $name;
    }

    /**
     * @param Student $student
     * @return string
     * @throws \setasign\Fpdi\PdfReader\PdfReaderException
     */
    public function generatePdfNewVersion(Student $student, Promo $promo){
        // initiate FPDI
        $pdf = new Fpdi();
        // set the source file
        $pdf->setSourceFile($this->template_directory . "template_ecf_2.pdf");

        // Generate page 1

        // import page 1
        $tplIdx = $pdf->importPage(1);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getAdress()), 75.5, 173);
        $this->setSimpleTxt($pdf, utf8_decode($student->getName()), 75.5, 193);
        $this->setSimpleTxt($pdf, utf8_decode($student->getFirstname()), 75.5, 199);
        $this->setSimpleTxt($pdf, $student->getDateOfBirth()->format('d-m-Y'), 75.5, 205);

        if ($student->getGender() != 0){
            $this->setValidation($pdf, utf8_decode($student->getGender()), 90, 186.8, 110, 186.8);
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

//         import page 5
        $tplIdx = $pdf->importPage(5);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityOne(), 15, 71.4, 15, 79);
        $this->setLongText($pdf, utf8_decode($student->getCommActivityOne()), 19, 102);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 245);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 245);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 236,25);

//         import page 6
        $tplIdx = $pdf->importPage(6);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateEvalSuppOne(), 16, 159.4, 16, 167);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 251);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 251);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 243,25);

//        Import page 7
        $tplIdx = $pdf->importPage(7);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

// import page 8
        $tplIdx = $pdf->importPage(8);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityTwo(), 15, 66, 15, 73);
        $this->setLongText($pdf, utf8_decode($student->getCommActivityTwo()), 19, 97);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 250);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 250);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 240,25);


        // import page 9
        $tplIdx = $pdf->importPage(9);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateEvalSuppTwo(), 16, 157, 16, 165);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 248);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 248);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 154, 238,25);

        // import page 10
        $tplIdx = $pdf->importPage(10);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidation($pdf, $student->getValidateActivityOne(), 75, 142, 94, 142);
        $this->setValidation($pdf, $student->getValidateActivityTwo(), 75, 184, 94, 184);

//         import page 11
        $tplIdx = $pdf->importPage(11);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setLongText($pdf, utf8_decode($student->getObservationStudent()), 19, 70);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getTrainer()), 37, 151);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 151);
        $this->setSimpleTxt($pdf, utf8_decode($promo->getCampusManager()), 37, 174.5);
        $this->setSimpleTxt($pdf,(new \DateTime())->format('d-m-Y'), 105, 174);

        $this->setSign($pdf, $this->signDirectory . $promo->getSignTrainer(), 156, 142,25);
        $this->setSign($pdf, $this->signDirectory . $promo->getSignCM(), 156, 164,25);

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

    /**
     * @param Fpdi $pdf
     * @param $img
     * @param $x
     * @param $y
     */
    private function setSign(Fpdi $pdf, $img, $x, $y, $w){
        if ($img != $this->signDirectory){
            $pdf->Image($img,$x,$y,$w);
        }
    }
}
