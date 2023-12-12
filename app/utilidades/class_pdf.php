<?php
    class PDF extends FPDF
    {
        function Header()
        {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(80);
            $this->Cell(30, 10, 'Lista Usuarios', 1, 0, 'C');
            $this->Ln(20);
        }

        function Footer()
        {
            $this->SetY(-15);
            $this->SetFont('Arial', 'I', 8);
            $this->Cell(0, 10, 'Página ' . $this->PageNo(), 0, 0, 'C');
        }

        function ChapterTitle($title)
        {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, $title, 0, 1);
        }

        function ChapterBody($body)
        {
            $this->SetFont('Arial', '', 12);
            $this->MultiCell(0, 10, $body);
        }
    }
?>