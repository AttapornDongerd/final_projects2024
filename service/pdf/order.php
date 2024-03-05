<?php
require_once('../../service/connect.php');

function getOrderInfo($ord_id)
{
    global $conn;
    $sql = "SELECT order_sewing.*, employees.emp_name, customer.cus_name, design.starting_price, design.design_image, queu_e.queue_datefinish
    FROM order_sewing 
    JOIN employees ON order_sewing.emp_id = employees.emp_id
    JOIN customer ON order_sewing.cus_id = customer.cus_id
    JOIN design ON order_sewing.design_id = design.design_id
    JOIN queu_e ON order_sewing.ord_id = queu_e.ord_id
    WHERE order_sewing.ord_id = :ord_id";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(":ord_id", $ord_id, PDO::PARAM_STR);
    $stmt->execute();
    $orderInfo = $stmt->fetch(PDO::FETCH_ASSOC);
    return $orderInfo;
}

function generateOrderPDF($ord_id)
{
    $orderInfo = getOrderInfo($ord_id);
    require_once('../../TCPDF-main/tcpdf.php');

    class CustomPDF extends TCPDF
    {
        public $orderInfo;
        public function __construct($orderInfo)
        {
            parent::__construct();
            $this->orderInfo = $orderInfo;
        }
        // Add custom Header() method
        public function Header()
        {
            $this->SetMargins(10, 10, 10, 10); // left, top, right, bottom
            $html = '';

            $html .= '<table style="margin: auto; line-height: 8px;"><tr>'
                . '<td width="70" style="font-weight: bold;"><br><br><br><br><img style="margin-top: auto" src="../../assets/images/logo1.jpg' . '" width="60" /></td>'
                . '<td width="400" height="15" align="center">'
                . '<p></p>'
                . '<h3 style="font-size: 20px; font-weight: bold;">ร้านแพรวาบูติค</h3>'
                . '<h3 style="font-size: 20px; font-weight: bold;">ต.เกษตรวิสัย อ.เกตรวิสัย จ.ร้อยเอ็ด</h3>'
                . '<br>'
                . '</td></tr>'
                . '</table>';


            $this->SetFont('thsarabunnew', '', 10);
            $this->writeHTML($html, true, false, true, false, '');

            $this->setY(35);
            $this->SetLineWidth(0.1);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            // รีเซ็ต margin เป็นค่าเริ่มต้น (หากต้องการ)
            $this->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT, PDF_MARGIN_BOTTOM);
        }
        // Add custom Footer() method
        public function Footer()
        {
            $this->SetY(-12);
            $this->SetLineWidth(0.1);
            $this->Line(10, $this->GetY(), 200, $this->GetY());
            $this->SetFont('thsarabunnew', 'I', 8);
            $thaiMonths = array(
                'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
            );
            $thaiDateTime = date('วันที่ j เดือน ') . $thaiMonths[(int)date('m') - 1] . date(' พ.ศ. Y เวลา H:i น.', strtotime('+543 years'));
            $this->Cell(60, 10, 'ออกรายงานเมื่อ ' . $thaiDateTime, 0, 0, 'L');
            $this->Cell(130, 10, 'หน้า ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, 0, 'R');
        }
    }

    // Create new PDF object
    $pdf = new CustomPDF($orderInfo);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Author');
    $pdf->SetTitle('Order Receipt');
    $pdf->SetSubject('Order Details');
    $pdf->SetKeywords('Order, Details');

    $pdf->AddPage();

    $pdf->SetFont('thsarabunnew', '', 14);
    $pdf->setY(40);
    // Add content
    $html = '<h1 style="font-size: 18px; font-weight: bold; text-align: center margin-top: 10px;">ใบแสดงรายละเอียดสั่งตัดชุด</h1>';
    $html .= '<div style="margin-top: 8px;">';
    $html .= '<p><strong>รหัสสั่งตัดชุด:</strong> ' . $orderInfo['ord_id'] . '</p>';
    $html .= '<p><strong>ชื่อพนักงาน:</strong> ' . $orderInfo['emp_name'] . '</p>';
    $html .= '<p><strong>วันที่สั่งตัด:</strong> ' . $orderInfo['ord_date'] . '</p>';
    $html .= '<p><strong>วันที่เสร็จสิ้น:</strong> ' . $orderInfo['queue_datefinish'] . '</p>';
    $html .= '<p><strong>ชื่อลูกค้า:</strong> ' . $orderInfo['cus_name'] . '</p>';
    $html .= '<div style="float: right;"><p><strong>แบบชุด:</strong> <img src="../../assets/images/' . $orderInfo['design_image'] . '" style="width: 80px;"></p></div>';
    $html .= '<div style="text-align: left; margin-top: 10px;">
    <label style="font-size: 16px;" class="form-label"> รายละเอียดสั่งตัดชุด </label> <br>
</div>';
    $html .= '</div>';

    $html .= '<table class="table p-1">';
    $html .= '<tr>';
    // $html .= '<th style="width: 5%; text-align: center; font-weight: bold;">No.</th>';
    $html .= '<th style="width: 28%; font-weight: bold;">รายละเอียดวัดตัว</th>';
    $html .= '<th style="width: 12%; font-weight: bold;">ขนาดผ้า</th>';
    $html .= '<th style="width: 12%; font-weight: bold;">ราคาเริ่มต้น</th>';
    $html .= '<th style="width: 10%; font-weight: bold;">ค่าตัดชุด</th>';
    $html .= '<th style="width: 22%; font-weight: bold;">รายละเอียดเพิ่มเติม</th>';
    $html .= '<th style="width: 15%; font-weight: bold;">ราคาเพิ่มเติม</th>';
    $html .= '</tr>';
    $html .= '<tbody>';

    $html .= '<tr>';
    // $html .= '<td style="width: 5%; text-align: center">1</td>';
    $html .= '<td style="width: 28%">' . $orderInfo['detail_measure'] . '</td>';
    $html .= '<td style="width: 12%">' . $orderInfo['ord_size'] . '</td>';
    $html .= '<td style="width: 12%">' . $orderInfo['starting_price'] . '</td>';
    $html .= '<td style="width: 10%">' . $orderInfo['order_price'] . '</td>';
    $html .= '<td style="width: 22%">' . $orderInfo['detailmore'] . '</td>';
    $html .= '<td style="width: 15%">' . $orderInfo['price_detailmore'] . '</td>';
    $html .= '</tr>';
    $html .= '</tbody>';
    $html .= '</table>';
    $html .= '<div style="text-align: right; margin-top: 10px;">';
    $html .= '<p><strong>ราคารวม:</strong> ' . $orderInfo['total_price'] . '</p>';
    $html .= '</div>';

    // Set HTML content
    $pdf->writeHTML($html, true, false, true, false, '');

    // Output PDF
    $pdf->Output('order_receipt.pdf', 'I');
}
