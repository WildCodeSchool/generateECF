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

        $this->setCampus($pdf, $student->getPromo()->getCity()->getName());
        $this->setName($pdf, $student->getName());
        $this->setFirstname($pdf, $student->getFirstname());
        $this->setDateBirth($pdf, $student->getDateOfBirth());
        $this->setGender($pdf, $student->getGender());

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


        // import page 5
        $tplIdx = $pdf->importPage(5);
        $pdf->AddPage('P');
        // use the imported page
        $pdf->useTemplate($tplIdx);
        $this->setCommActivityOne($pdf, $student->getCommActivityOne());

        $filename = $student->getName() . '_' . $student->getFirstname() . '_ecf.pdf';
        if (!file_exists($this->output . str_replace(' ', '_', $student->getPromo()->getName()))) {
            mkdir($this->output . str_replace(' ', '_', $student->getPromo()->getName()), 0777, true);
        }

//        TODO: For test
//        $pdf->Output();

        $pdf->Output('F', $this->output . str_replace(' ', '_', $student->getPromo()->getName()) . '/' . $filename);
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

    //    TODO: Modifier coordonnes
    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setCommActivityOne(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        // Sortie du texte justifié
        $pdf->MultiCell(80,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationEvalActivityOne(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(90, 188.8);
        } elseif ($value == false){
            $pdf->SetXY(110, 188.8);
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
        // Sortie du texte justifié
        $pdf->MultiCell(0,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationlActivityTwo(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(90, 188.8);
        } elseif ($value == false){
            $pdf->SetXY(110, 188.8);
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
        // Sortie du texte justifié
        $pdf->MultiCell(0,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $value
     */
    public function setValidationEvalActivityTwo(Fpdi $pdf, $value){
        if ($value == true){
            $pdf->SetXY(90, 188.8);
        } elseif ($value == false){
            $pdf->SetXY(110, 188.8);
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
        // Sortie du texte justifié
        $pdf->MultiCell(0,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }

    /**
     * @param Fpdi $pdf
     * @param $comm
     */
    public function setObservationStudent(Fpdi $pdf, $comm){
        $pdf->SetFont('Helvetica','', 10);
        // Sortie du texte justifié
        $pdf->MultiCell(0,5,$comm);
        // Saut de ligne
        $pdf->Ln();
    }
}
