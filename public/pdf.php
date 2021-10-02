<?php

    include 'pdf/src/Cezpdf.php'; // Or use 'vendor/autoload.php' when installed through composer

    // Initialize a ROS PDF class object using DIN-A4, with background color gray
    $pdf = new Cezpdf('a4','portrait','color',[1,1,1]);
    // Set pdf Bleedbox
    $pdf->ezSetMargins(20,20,20,20);
    // Use one of the pdf core fonts
    $mainFont = 'Times-Roman';
    // Select the font
    $pdf->selectFont($mainFont);
    // Define the font size
    $size=12;
    // Modified to use the local file if it can
    $pdf->openHere('Fit');

    // Output some colored text by using text directives and justify it to the right of the document
    $pdf->ezText("PDF with some text", $size, ['justification'=>'right']);
    // Output the pdf as stream, but uncompress
    $pdf->ezStream(['compress'=>0]);
?>