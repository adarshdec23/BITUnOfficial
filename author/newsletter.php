<?php
/*
  Project name : BITuN
  Start Date : 4 Jun, 2015 2:40:18 PM
  Author: Adarsh
  Purpose :
 */
session_start();
if(!isset($_SESSION['author_id']) || $_SESSION['author_id'] != 1){
	echo $_SESSION['author_id'];
	die();
    header("LOCATION: /");
}

$message = '<!DOCTYPE HTML>
<html>
<head>
<title>BITUnOfficial</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style type="text/css">
a:link, a:visited {
	color: #FFFFFF;
	text-decoration:none;
}
a:hover {
	color: #59b8cc;
}
</style>
</head>
<body bgcolor="#161616" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top" bgcolor="#161616"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="font: 14px Helvetica, Arial, sans-serif; color: #767572; line-height: 100%;">
        <td valign="top"><table width="600" height="150px" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top" style="width: 504px; height: 125px;"><table width="100%" border="0" cellspacing="0" cellpadding="0" style="height: 125px;">
              <tr>
                <td valign="top" bgcolor="#161616" style="height: 10px;">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" bgcolor="#161616" style="height: 50px; font-family: Helvetica, Arial, sans-serif; font-size: 55px; font-weight: bold; color: #f9f8f2; letter-spacing: -2px; line-height: 90%;"> <a href="http://BITUnOfficial.com" style="color: #FFFFFF; text-decoration:none;">BITUnOfficial <a/></td>
              </tr>
              <tr>
                <td valign="top" bgcolor="#161616" style="height: 25px; font-family: Helvetica, Arial, sans-serif; font-size: 19px; font-weight: bold; color: #FFFFFF;"> The <span style="color:#FF0000">Un</span>Official Website </td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      <tr>
        <td valign="top" bgcolor="#161616"><table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td style="font-family: Helvetica, Arial, sans-serif; font-size: 30px; font-weight: bold; color: #767572; letter-spacing: -2px;">Best of Luck for Your Exams !</td>
              </tr>
              <tr>
                <td style="height: 30px;">&nbsp;</td>
              </tr>
              <tr>
                <td><img src="http://BITUnOfficial.com/Images/Email/Email_London.jpg" width="600" height="269" alt=""></td>
              </tr>
              <tr>
                <td style="height: 30px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-family: Helvetica, Arial, sans-serif; font-size: 30px; font-weight: bold; color: #767572; letter-spacing: -2px;">BITUnOfficial wishes you all the best !</td>
              </tr>
              <tr>
                <td style="height: 30px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="font-family: Helvetica, Arial, sans-serif; color: #767572;"> With exams coming ever closer, BITUnOfficial would like to wish you all the best (or if you just finished your 8th semester, good luck for your future endeavours). We know exams can be stressful so take tiny break between your study time and catch up to your college. Check out what we\'ve got running here at BITUnOfficial.</td>
              </tr>
              <tr>
                <td style="height: 20px;">&nbsp;</td>
              </tr>
              <tr>
                <td style="height: 30px;"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td width="30%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="font-family: Helvetica, Arial, sans-serif; font-size: 25px; font-weight: bold; color: #767572; letter-spacing: -2px; padding-bottom: 10px;"><a href="http://BITUnOfficial.com/chat" style="color: #FFFFFF; text-decoration:none;">BIT Chat</a></td>
                      </tr>
                      <tr>
                        <td valign="top" style="font-family: Helvetica, Arial, sans-serif; color: #767572; line-height: 130%;">Chat with people from different branches and classes. Check out our newly implemented chat feature that lets you hang out with other people from college.<a href="http://BITUnOfficial.com/chat" style="color: #61c7dd;">Chat now</a></td>
                      </tr>
                    </table></td>
                    <td width="5%" valign="top">&nbsp;</td>
                    <td width="30%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="font-family: Helvetica, Arial, sans-serif; font-size: 25px; font-weight: bold; color: #767572; letter-spacing: -2px; padding-bottom: 10px;"><a href="http://bitunofficial.com/art/38/BIT_Placement_Details" style="color: #FFFFFF; text-decoration:none;">Placement Details</a></td>
                      </tr>
                      <tr>
                        <td valign="top" style="font-family: Helvetica, Arial, sans-serif; color: #767572; line-height: 130%;"> Worried about placements? Check out this years placement details released by BIT Technoholix together with the BIT placement department. <a href="http://bitunofficial.com/art/38/BIT_Placement_Details" style="color: #61c7dd;">Read more</a></td>
                      </tr>
                    </table></td>
                    <td width="5%" valign="top">&nbsp;</td>
                    <td width="30%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="font-family: Helvetica, Arial, sans-serif; font-size: 25px; font-weight: bold; color: #767572; letter-spacing: -2px; padding-bottom: 10px;"><a href="https://www.facebook.com/BITUnOfficial" style="color: #FFFFFF; text-decoration:none;">Facebook Us</a></td>
                      </tr>
                      <tr>
                        <td valign="top" style="font-family: Helvetica, Arial, sans-serif; color: #767572; line-height: 130%;"> If you like us, then like us on Facebook too if you haven\'t already. <a href="https://www.facebook.com/BITUnOfficial" style="color: #61c7dd;">Our Facebook</a></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td style="height: 30px;">&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
        </tr>
      <tr>
        <td><table width="600" border="0" cellpadding="0" cellspacing="0" style="padding-top: 10px;">
          <tr>
            <td colspan="6"><img src="images/footer_05.gif" width="599" height="25" alt=""></td>
            <td width="1" rowspan="3"><img src="images/footer_02.gif" width="1" height="140" alt=""></td>
          </tr>
          <tr>
            <td width="179" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="10%" valign="top">&nbsp;</td>
                <td width="69%" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td style="font-family: Helvetica, Arial, sans-serif; font-size: 17px; font-weight: bold; color: #f9f8f2;">Email Friend:</td>
                      </tr>
                    </table></td>
                  </tr>
                  <tr>
                    <td valign="top">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px; color: #767572;">Know someone who might be interested? <br /> <forwardtoafriend>Forward it to them</forwardtoafriend>.
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table>
              <p style="font-size: 16px; font-weight: bold; margin: 0; padding: 0 0 6px 0;">&nbsp;</p></td>
            <td width="30" valign="top">&nbsp;</td>
            <td width="36" rowspan="2" valign="top"><img src="http://bitunofficial.com/Images/Logo.ico" width="36" height="37" alt=""></td>
            <td width="16" rowspan="2" valign="top">&nbsp;</td>
            <td width="334" rowspan="2" valign="top"><p style="font-family: Helvetica, Arial, sans-serif; font-size: 16px; font-weight: bold; color: #767572; margin: 0; padding: 0 0 6px 0;">BITUnOfficial</p>
              <p style="font-family: Helvetica, Arial, sans-serif; font-size: 11px; color: #767572; margin: 0; padding: 0; letter-spacing: -0.3px;"> The UnOfficial Website. </p></td>
          </table></td>
      </tr>
    </table></td>
  </tr>
</table>
</body>
</html>';

$message=  wordwrap($message,67,"\r\n");
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
$headers .= 'From: BIT UnOfficial <admin@BITUnOfficial.com>' . "\r\n";
$subject = "Best of luck for your exams !";

include 'include/aCon.php';
/*
 * Get the names of all people
 */
$result = $aLink->query("SELECT email_id FROM news_sub");
while($row = $result->fetch_assoc())
{
	$to = $row['email_id'];
	$headers .= 'To: '.$row['email_id']. "\r\n";
	if(!mail($to,$subject,$message,$headers))
	echo "$to <br>";
}
?>

