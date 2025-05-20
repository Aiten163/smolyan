<?php
header('Access-Control-Allow-Origin: *'); 
//echo 'ContentType: text/xml';
// ������������� ��������� XML
echo '<?xml version="1.0" encoding="UTF8" standalone="yes"?>';
// ������� ������� <response>
echo '<response>';

// �������� ���� ������
$server="localhost";
$user="root";
$password="";
if (!mysql_connect($server, $user, $password)) 
   {
    echo "������ ����������� � ������� MySQL";
    exit;
   }

mysql_query('SET NAMES UTF8');

$db="currency";
mysql_select_db($db);
if (mysql_errno() <> "0")
    echo "ERROR ".mysql_errno()." ".mysql_error()."\n";

    $god = mt_rand(2016,2018); // ��� - ���������
    $zglv = '�������� ������ �����. ���: '.$god."<BR>";

    $myquery = 'select `CDate`, `dollar`, `euro`,'.
               ' `GBP` FROM `tcurrency` where year(`CDate`) ='.$god.' ORDER BY CDate';
    $res = mysql_query($myquery);

    if ($row = mysql_fetch_array($res, MYSQL_ASSOC))
       {
         $perem1 = $row['CDate'];
         $perem2 = $row['dollar']; 
         $perem3 = $row['euro']; 
         $perem4 = $row['GBP'];
       }

echo '<B><font face="Courier" size= "5" color="navy">';
echo $zglv;
echo '</font></B>';
echo '<font face="Courier" size= "4" color="navy">';
echo '----------------------------------------------'."<BR>";
echo '<B>'.' ���� ........ ������ ... ���� ... ����.����'."<BR>"."</B>";
echo '----------------------------------------------'."<BR>";

echo '<font face="Courier" size= "4" color="maroon">';
echo $perem1.' .. '.$perem2.' ... '.$perem3.' ..... '.$perem4."<BR>";
echo '</font>';
echo '----------------------------------------------'."<BR>";

// ������� ������� <response>
echo '</response>';

?>
