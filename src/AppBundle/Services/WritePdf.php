<?php

namespace AppBundle\Services;

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
    public function generatePdf(Student $student){
        // initiate FPDI
        $pdf = new Fpdi();
        // set the source file
        $pdf->setSourceFile($this->template_directory . "php.pdf");

        // Generate page 1

        // import page 1
        $tplIdx = $pdf->importPage(1);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setCampus($pdf, utf8_decode($student->getPromo()->getCity()->getName()));
        $this->setName($pdf, utf8_decode($student->getName()));
        $this->setFirstname($pdf, utf8_decode($student->getFirstname()));
        $this->setDateBirth($pdf, $student->getDateOfBirth());
        $this->setGender($pdf, utf8_decode($student->getGender()));

        $tplIdx = $pdf->importPage(2);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

        $tplIdx = $pdf->importPage(3);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

        // import page 4
        $tplIdx = $pdf->importPage(4);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);

        $this->setValidationlActivityOne($pdf, $student->getValidateActivityOne());


//         import page 5
        $tplIdx = $pdf->importPage(5);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setCommActivityOne($pdf, utf8_decode($student->getCommActivityOne()));

//         import page 6
        $tplIdx = $pdf->importPage(6);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidationEvalActivityOne($pdf, $student->getValidateEvalSuppOne());
        $this->setCommEvalActivityOne($pdf, utf8_decode($student->getCommEvalSuppOne()));

//        Import page 7
        $tplIdx = $pdf->importPage(7);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

        // import page 8
        $tplIdx = $pdf->importPage(8);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidationlActivityTwo($pdf, $student->getValidateActivityTwo());
        $this->setCommActivityTwo($pdf, utf8_decode($student->getCommActivityTwo()));

        // import page 9
        $tplIdx = $pdf->importPage(9);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setValidationEvalActivityTwo($pdf, $student->getValidateEvalSuppTwo());
        $this->setCommEvalActivityTwo($pdf, utf8_decode($student->getCommEvalSuppTwo()));

        // import page 10
//        $tplIdx = $pdf->importPage(10);
//        $pdf->AddPage('P');
//        // use the imported page
//        $pdf->useTemplate($tplIdx);
//        $this->setValidationEvalActivityTwo($pdf, $student->getValidateEvalSuppTwo());
//        $this->setCommEvalActivityTwo($pdf, utf8_decode($student->getCommEvalSuppTwo()));

//        Import page 11
        $tplIdx = $pdf->importPage(11);
        $pdf->AddPage('P');
        $pdf->useTemplate($tplIdx);

//         import page 12
        $tplIdx = $pdf->importPage(12);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setObservationStudent($pdf, utf8_decode($student->getObservationStudent()));

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
     * @param $name
     */
    private function setName(Fpdi $pdf, $name){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(75.5, 194);
        $pdf->Write(0, $name);
    }

    /**
     * @param Fpdi $pdf
     * @param $firstname
     */
    private function setFirstname(Fpdi $pdf, $firstname){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(75.5, 200);
        $pdf->Write(0, $firstname);
    }

    /**
     * @param Fpdi $pdf
     * @param \DateTime $date
     */
    private function setDateBirth(Fpdi $pdf, \DateTime $date){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(75.5, 206);
        $pdf->Write(0, $date->format('d-m-Y'));
    }

    /**
     * @param Fpdi $pdf
     * @param $campus
     */
    private function setCampus(Fpdi $pdf, $campus){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(75.5, 176);
        $pdf->Write(0, $campus);
    }

    /**
     * @param Fpdi $pdf
     * @param $gender
     */
    private function setGender(Fpdi $pdf, $gender){
        if ($gender == Student::FEMALE){
            $pdf->SetXY(90, 188.8);
        } elseif ($gender == Student::MALE){
            $pdf->SetXY(110, 188.8);
        }
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(0, 0, '4', 0, 0);
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationlActivityOne(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(16, 214);
        } elseif ($value == false){
            $pdf->SetXY(16, 221);
        }
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(0, 0, '4', 0, 0);
    }

    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setCommActivityOne(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(19, 63);

        // Sortie du texte justifié
        $pdf->MultiCell(170,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationEvalActivityOne(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(16, 145);
        } elseif ($value == false){
            $pdf->SetXY(16, 156);
        }
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(0, 0, '4', 0, 0);
    }


    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setCommEvalActivityOne(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(19, 180);

        // Sortie du texte justifié
        $pdf->MultiCell(170,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationlActivityTwo(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(16, 102);
        } elseif ($value == false){
            $pdf->SetXY(16, 109);
        }
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(0, 0, '4', 0, 0);
    }

    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setCommActivityTwo(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(19, 135);

        // Sortie du texte justifié
        $pdf->MultiCell(170,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationEvalActivityTwo(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(16, 165);
        } elseif ($value == false){
            $pdf->SetXY(16, 175);
        }
        $pdf->SetFont('ZapfDingbats','', 10);
        $pdf->Cell(0, 0, '4', 0, 0);
    }

    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setCommEvalActivityTwo(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(19, 197);

        // Sortie du texte justifié
        $pdf->MultiCell(170,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setObservationStudent(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        $pdf->SetXY(19, 73);

        // Sortie du texte justifié
        $pdf->MultiCell(170,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }
}
