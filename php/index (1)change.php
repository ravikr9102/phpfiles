<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type, X-Requested-With");

// Include the database connection file
include '../../database/db_connection.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
include '../../vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode JSON data
    $json_data = json_decode(file_get_contents("php://input"), true);

    $lightfinder1 = $json_data['lightfinder1'];
    $type_of_lighting = $json_data['type_of_lighting'];
    $lighting_position = $json_data['lighting_position'];
    $design_style = $json_data['design_style'];
    $application_area_and_dimensions_area = $json_data['application_area_and_dimensions_area'];
   
    
    $filesUploaded = $json_data['filesUploaded'];
    $budget = $json_data['budget'];

    $contacted = $json_data['contacted'];
    $comments = $json_data['comments'];
    $email = $json_data['email'];

    $query = "SELECT MAX(report_no) AS last_report_number FROM light_finder";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();
    $lastReportNumber = $row['last_report_number'];

    $newReportNumber = $lastReportNumber ? $lastReportNumber + 1 : 1;
    $formattedReportNumber = sprintf("%010d", $newReportNumber);
    
    // Prepare and bind statement
    $stmt = $conn->prepare("INSERT INTO light_finder (report_no, lightfinder1, type_of_lighting, lighting_position, design_style, application_area_and_dimensions_area, filesUploaded, budget,contacted, comments, email) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if ($stmt === false) {
        echo json_encode(array("status" => "error", "message" => $conn->error));
    } else {
        $stmt->bind_param("sssssssssss", $formattedReportNumber, $lightfinder1, $type_of_lighting, $lighting_position, $design_style, $application_area_and_dimensions_area, $filesUploaded, $budget, $contacted, $comments, $email);

        // Execute the statement
        // $stmt->execute();
        $downloadLink ="";
        if ($stmt->execute()) {
            

            $logo = file_get_contents(__DIR__ . '/../../logo.png');
            $base64Image = base64_encode($logo);

            $lightfinder1Records = json_decode($lightfinder1, true);
            $type_of_lightingRecords = json_decode($type_of_lighting, true);
            $lighting_positionRecords = json_decode($lighting_position, true);
            $dimenAreaRecords = json_decode($application_area_and_dimensions_area, true);
            $budgetRecords = json_decode($budget, true);
            $uploadedImages = json_decode($filesUploaded, true);
            // Base URL for images
            $baseUrl = "https://kaash.eu/uploads/";
            $filesImages = '';
            
            foreach ($uploadedImages as $index => $image) {
                              
                $filesImages .= '<a href="' . htmlspecialchars($baseUrl . $image) . '" target="_blank">File '.($index + 1).'</a><br>';
            }
            
            $html = '';
            $html .='<!doctype html>
            <html lang="en">
            <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Document</title>
            <style>
                @font-face {
                    font-family: \'Poppins Regular\';
                    src: url(\'path/to/poppins-regular.woff2\') format(\'woff2\'),
                        url(\'path/to/poppins-regular.woff\') format(\'woff\');
                        /* Font weight for Regular */
                    font-weight: 400;
                    font-style: normal;
                }
        
                @font-face {
                    font-family: \'Poppins Light\';
                    src: url(\'path/to/poppins-light.woff2\') format(\'woff2\'),
                        url(\'path/to/poppins-light.woff\') format(\'woff\');
                    font-weight: 300; /* Font weight for light */
                    font-style: normal;
                }
        
                *, body {
                    margin: 0;
                    padding: 0;
                    font-family: \'Poppins\', sans-serif;
                    font-size: 15px;
                    background:#f4f4f5;
                }
        
                .light-text {
                    font-family: "Poppins Light", sans-serif;
                    /* Other styling properties */
                }
        
                table {
                    width: 100%;
                }
        
                tbody{
                    vertical-align: baseline;
                }
        
                .custom-td {
                    padding-bottom: 27px;
                }
                .textColor{
                    color:#18181B;
                }

                .backgroundColor{
                    background:#f4f4f5;
                }

                .purplecolor{
                    color:#7246FD;
                }

                .fotterTextColor{
                    color:#a1a1aa;
                }
                .fotterTextFixed {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    width: 100%; /* Optional: set width to 100% for full width */
                  }

            </style>
            </head>
            <body style="padding: 1.3rem">
            <table width="100%" style="border-bottom: 0.1rem solid #A1A1AA;padding-bottom: 4px;">
                <tbody>
                <tr style="">
                    <td style="width: 90%"><p style="font-weight: light;padding-left: 1rem;">Report N.'.$formattedReportNumber.'</p></td>
                    <td style="text-align: end;width: 10%;padding-right: 1rem;"><img style="margin-left: auto;width:100px;"
                    src="data:image/png;base64,' . $base64Image . '"
                    alt=""></td>
                </tr>
                </tbody>
            </table>
            <div style="margin: 4rem;">
                <h1 class="purplecolor" style="font-size: 2rem">Light Finder</h1>
                <table style="margin-top: 4rem;" class="textColor">
                    <tbody>
                        <tr>
                            <td style="width: 40vw"><b>E-mail</b></td>
                            <td>' . $email . '</td>
                        </tr>
                        <tr>
                            <td style="padding-top: .7rem;width: 40vw" width="30%"><b>Date</b></td>
                            <td style="padding-top: .7rem;">'.date('d/m/Y').'</td>
                        </tr>
                    </tbody>
                </table>
                <table style="margin-top:4rem;" class="textColor">
                    <tbody>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Location</b></td>
                            <td class="custom-td">'.$lightfinder1Records['needLightFor'].'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Type of lighting</b></td>
                            <td class="custom-td">'.implode(',',$type_of_lightingRecords).'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Light position</b></td>
                            <td class="custom-td">'.implode(',',$lighting_positionRecords).'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Design Style</b></td>
                            <td class="custom-td">'.$design_style.'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Application area</b></td>
                            <td class="custom-td">'.$dimenAreaRecords['applicationArea'].'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Space dimensions (Length, Breadth, Height)</b></td>
                            <td class="custom-td">' . 
                            (isset($dimenAreaRecords['dimensionsOrArea']['length']) ? $dimenAreaRecords['dimensionsOrArea']['length'] . ' ' . $dimenAreaRecords['dimensionsOrValue'] . ', ' : '') .
                            (isset($dimenAreaRecords['dimensionsOrArea']['breadth']) ? $dimenAreaRecords['dimensionsOrArea']['breadth'] . ' ' . $dimenAreaRecords['dimensionsOrValue'] . ', ' : '') .
                            (isset($dimenAreaRecords['dimensionsOrArea']['height']) ? $dimenAreaRecords['dimensionsOrArea']['height'] . ' ' . $dimenAreaRecords['dimensionsOrValue'] : '') .
                            '</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Space Area</b></td>
                            <td class="custom-td">'.
                                ($dimenAreaRecords['type'] =="area" ? $dimenAreaRecords['dimensionsOrArea']. ' ' . $dimenAreaRecords['dimensionsOrValue'] . ', ' : '')
                            .'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>File(s) uploaded</b></td>
                            <td class="custom-td">'.$filesImages.'</td>
                        </tr>
                        <tr>
                            <td style="width: 40vw" class="custom-td"><b>Budget</b></td>
                            <td class="custom-td">' . $budgetRecords['currency'] . ' ' $budgetRecords  ['amount'] . '</td>
                        </tr>
                    </tbody>
                </table>

            </div>

            <table width="100%" style="border-bottom: 0.1rem solid #757575;padding-bottom: 17px;padding-top:20px">
            
            </table>

            <div style="margin: 1rem;">
                <table style="margin-top: 1.5rem;" class="textColor">
                    <tbody>
                        <tr>
                            <td style="width: 40vw;padding-bottom: 5px;"><b>Contact via </b></td>
                            <td class="padding-bottom: 5px;">'.$contacted.'</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="margin: 1rem;">
                <table >
                    <tbody>
                        <tr>
                            <td style="width: 40vw;padding-bottom: 10px;" ><b>Comments</b></td>
                        </tr>
                        <tr>
                            <td style="width: 100vw" >'.$comments.'</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table width="100%" style="border-bottom: 0.1rem solid #A1A1AA;padding-top: 2px;">
            
            </table>
            <div clas="fotterTextFixed" style="margin: 1rem;">
            <table class="fotterTextColor" style="font-size: 10px;" >
                    <tbody style="vertical-align: baseline;">
                    <tr class="" style="">
                        <td class="" style="padding-right: 2rem;font-size: 10px;line-height: 1.2">
                            Kaash Light Engineers <br />
                            4la rue des romains
                            8041, Luxembourg
                        </td>
                        <td class="" style="padding-right: 2rem;font-size: 10px;line-height: 1.2">
                            moien@kaash.eu
                            <br />

                            +352 691 566 820
                            <br />

                            www.kaash.eu
                        </td>
                        <td style="font-size: 10px;text-align: end;line-height: 1.2">
                            Registre De Commerce Et Des Sociétés - A43604
                            <br />

                            LU Business registration - 10133294/0
                            <br

                            BGL BNP IBAN LU55 0030 1840 5176 0000
                            <br />

                            VAT LU34096506
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            </body>
            </html>';
            
            $options = new \Dompdf\Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('defaultFont', 'DejaVu Sans'); // Set the desired font
            $reportpdf = new \Dompdf\Dompdf($options);
            $reportpdf->setPaper('A4', 'portrait'); // 'portrait' for portrait orientation, 'landscape' for landscape
            $reportpdf->loadHtml($html);
            $reportpdf->render();
            
            // Save the rendered PDF content to a variable
            $pdfContent = $reportpdf->output();
            // Save the rendered PDF content to a file
            $randomNumber = mt_rand(100000, 999999); // Adjust the range as needed
            $pdfFilePath = __DIR__ . "/reports/{$randomNumber}_Report.pdf";
           
            file_put_contents($pdfFilePath, $pdfContent);

            // Create a download link
            $downloadLink = "https://kaash.eu/api/lightFinder/reports/{$randomNumber}_Report.pdf";

            
            $formEmail = "moien@kaash.eu"; // Add your email 
            $formName = "Moien";
    
            $companyEmail = "moien@kaash.eu"; // Add Company Email
            
            $mail = new PHPMailer(true);
            
            try {
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'mail.kaash.eu';                       // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'moien@kaash.eu';           // SMTP username
                $mail->Password = 'D,Yoj,p?j%~5';                 // SMTP password
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to

                $mail->setFrom($formEmail, $formName);
                $mail->addAddress($email);
            
                // Attach the PDF file
                $mail->addStringAttachment($pdfContent, 'Report.pdf', 'base64', 'application/pdf');
            
                // Email content
                $mail->isHTML(true);
                $mail->Subject = 'Verification and Submission of PDF';
                $mail->Body = 'Thank you for your interest. We are pleased to inform you that the PDF document for light finder has been successfully generated and is attached to this email. <br>Thank you.';
            
                
                if(!$mail->send()) {
                    echo json_encode(array("status" => "error", "message" => $mail->ErrorInfo));
                }else{
                    $mail->clearAddresses();
                    $mail->addAddress($companyEmail, $formName);
                    $mail->Subject = 'Verification and Submission of PDF';

                    $mail->Body = 'New Light Finder Form Submission';

                    $mail->send();

                }
               
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
    
            
        } else {
            echo json_encode(array("status" => "error", "message" => $stmt->error));
        }
        // Close statement
        $stmt->close();

        echo json_encode(array("status" => "success", "message" => "Data inserted successfully","link"=>$downloadLink));
    }
}

// Close connection
$conn->close();
?>
