<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8" />
  <title>Главное меню</title>
  <style>
   .center_align { text-align: center; vertical-align: middle; }
   .colortext { font-family: 'Tahoma';
    font-size: 13pt; margin: 5px;
    background-color: rgb(226,255,200);
    color: navy;
   }
   TABLE {
    font-size: 13pt;
    width: 100%;
    border: none;
    background-color: #ffffaf;
    color: navy;
    text-align: center; padding: 2px; margin: 2px;
   }
   TD, TH {
    text-align: center;
    padding: 2px;
    background-color: rgba(183,255,184,0.70);
    color: navy;
    border: 1px solid navy;
   }
   TH {
    color: white;
    background-color: #FF1177;
   }
   .button1 { font-family: 'Tahoma';
           background-color: #008CBA; font-size: 16pt; padding: 4px 20px 4px 20px;
           border-radius: 10px; border: 2px solid silver; color: yellow; margin: 3px 3px 3px 3px;
           cursor: pointer; text-align: center;
           }
  .button1:hover {
        box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.39); }
  .div0 { font-family: 'Tahoma'; font-size: 16pt;
        color: green; background-color: rgba(256,113,184,0.65); width: auto; text-align: center; margin: 3px; padding: 4px; }
  div { font-family: 'Tahoma'; font-size: 16pt;
        color: green; background-color: rgb(250,240,130); width: 1000px; text-align: center; 
        margin: 2px; padding: 4px; margin-left: auto; margin-right: auto; }
  div:hover { box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.39); }
  .text_input { font-family: 'Tahoma';
        background-color: #FEFEFE; font-size: 13pt; padding: 6px 10px;
        border-radius: 5px; border: 1px solid #4CAF50; color: maroon;
        cursor: pointer; text-align: left; margin: 4px; }
  .text_input:hover {
           box-shadow: 0 12px 16px 0 rgba(0,0,0,0.24), 0 17px 50px 0 rgba(0,0,0,0.39); }
  .interface  {font-family: 'Tahoma'; font-size: 16pt; color: navy; background-color: rgba(250,240,130,0.6); 
                padding: 5px; width: 1000px; margin-top: 3px; margin-bottom: 4px; margin-left: auto; margin-right: auto;}
  </style>

  <script type="text/javascript">

     function RadioClick(idx,kol_vo)
     {
        for (k=1; k<=kol_vo; k++) { document.getElementById('_radio'+String(k)).checked = false; }
        document.getElementById('_radio'+String(idx)).checked = true;
        document.getElementById('_Master').value = document.getElementById('_master'+String(idx)).value;
        document.getElementById('_NewLog').value = document.getElementById('_slovo1'+String(idx)).value;
        document.getElementById('_NewPas').value = document.getElementById('_slovo2'+String(idx)).value;
        document.getElementById('_NewCom').value = document.getElementById('_comment'+String(idx)).value;
     }
  </script>

</head>
<body class="colortext">
<div class = "interface">
<table class="center_align" align="center" width="100%">
<tr>
<td><font color="navy" style="font-family: Tahoma; font-size: 17pt; font-weight: bold;">Л О Г И Н Ы - П А Р О Л И</font></td>
</tr>
</table>
</div>
<?php
function OutData($res_view,$num_rows)
{
          echo '<table>';
          echo '<th width="6%">Отм</th>';
          echo '<th width="6%">Спец</th>';
          echo '<th width="20%">Login</th>';
          echo '<th width="20%">Password</th>';
          echo '<th width="48%">Comment</th>';
          $idx = 1;
          while ( $row_view = $res_view->fetch_assoc() )
          {
             echo '<tr>';
             echo '<td>';
             echo '<input type="radio" name="radio'.$idx.'" id="_radio'.$idx.'" '.
                         'value="'.$row_view['id_sys'].'" onclick="RadioClick('.$idx.','.$num_rows.');">';
             echo '</td>';
             echo '<td>';
             echo '<input readonly class = "text_input" style="width: 30px;" type="text" id="_master'.$idx.'"'.
                  ' name="master'.$idx.'"'.' value="'.$row_view['master'].'">';
             echo '</td>';
             echo '<td>';
             echo '<input readonly class = "text_input" type="text" id="_slovo1'.$idx.'"'.
                  ' name="slovo1'.$idx.'"'.' value="'.$row_view['slovo1'].'">';
             echo '</td>';
             echo '<td>';
             echo '<input readonly type="text" class = "text_input" id="_slovo2'.$idx.'"'.
                  ' name="slovo2'.$idx.'"'.' value="'.$row_view['slovo2'].'">';
             echo '</td>';
             echo '<td>';
             echo '<input readonly type="text" class = "text_input" style="width:300px;" id="_comment'.$idx.'"'.
                  ' name="comment'.$idx.'"'.' value="'.$row_view['comment'].'">';
             echo '</td>';
             echo '</tr>';
             $idx++;
          }
          echo '</table>';
          echo '<input class = "button1" type="submit" value="Изменить" name="Edit">';
          echo '<input class = "text_input" style="font-size: 12pt; width: 20px;" 
                       type= "text" name = "Master" id="_Master" maxlength="1">';
          echo '<input class = "text_input" style="font-size: 12pt;" 
                       type= "text" name = "NewLog" id="_NewLog" maxlength="10" size="6">';
          echo '<input class = "text_input" style="font-size: 12pt;" 
                       type= "text" name = "NewPas" id="_NewPas" maxlength="10" size="7">';
          echo '<input class = "text_input" style="font-size: 12pt;" 
                       type= "text" name = "NewCom" id="_NewCom" maxlength="20" size="18">';
          echo '<input class = "button1" style="cursor: default; background-color: rgb(125,125,125);" 
                type="submit" disabled value="Удалить"  name="Delete">';
          echo '<input class = "button1" style="cursor: default; background-color: rgb(125,125,125);" 
                type="submit" disabled value="Добавить" name="Insert">';
}

   { 
   $log = trim($_GET['Admin']);
   if ( $log != "MyAdmin" )
   { 
      echo '
        <form method="post" action="">
        <div>
        <p class="center_align">Вход не разрешен...
        <input autofocus class = "button1" type="submit" value="О б н о в и т ь"></p>
        </div> 
        </form>
        </body>
        </html>';
      exit;
   }
   else
   {
     // ********************************************************
         require_once('MyConnect.php'); MyConnectOtvet();
     // ********************************************************
     if ( !$ConnectOtvet )
     {
       echo '<form method="post" action="">
         <div class = "interface" style="margin-top: -24px;">
         <p class="center_align">Ошибка соединения с сервером...
         <input autofocus class = "button1" type="submit" value="О б н о в и т ь"></p>
         </div> 
         </form>
        </body>
        </html>';
        exit();
     }
     $query_view = 'select `id_sys`, `master`, `slovo1`, `slovo2`, `comment` from `mysystem` order by master, slovo1,slovo2';
     $res_view = $mysqli->query($query_view);
     if ( !$res_view )
     {
        echo '<form method="post" action="">
              <div class = "interface" style="margin-top: -24px;">
              <p class="center_align">Ошибка чтения данных с сервера...
              <input autofocus class = "button1" type="submit" value="О б н о в и т ь"></p>
              </div>
              </form>
              </body>
              </html>';
        exit();
     }
     else // Есть данные...
     {
        $oper_done = false;
        echo '<form method="post" action="">';
        echo '<input hidden readonly type="text" name="SysAdminLogs" value="'.$_POST['SysAdminLogs'].'">';

        $num_rows = $res_view->num_rows;
        echo '<input hidden readonly type="text" name="KOL_VO" value="'.$num_rows.'">';
        if ( $num_rows == 0 )
        {
           echo '<div class = "interface" style="margin-top: -24px;">
                 <p class="center_align">Список логинов-паролей пуст...</p>
                 </div>';
        }
        else // есть записи логин-пароль
        {

          echo '<div class = "interface" style="margin-top: -24px;">';

          if (( !isset($_POST['Edit']) ) && ( !isset($_POST['Delete']) ) && ( !isset($_POST['Insert']) )) 
             { OutData($res_view,$num_rows); }

          if ( isset($_POST['Edit']) )
          {
            $oper_done = true;
            $NewLog = Trim($_POST['NewLog']);
            $NewPas = Trim($_POST['NewPas']);
            $NewMas = Trim($_POST['Master']);
            $NewCom = Trim($_POST['NewCom']);
            if ( ($NewLog=='') || ($NewPas=='') )
            {
              $oper_message = 'Не заданы новые Логин и Пароль...';
              $query_view = 'select `id_sys`, `master`, `slovo1`, `slovo2`, `comment` from `mysystem` order by master, slovo1,slovo2';
              $res_view = $mysqli->query($query_view);
              if ( !$res_view ) 
              { 
                 echo '<p class="center_align">Ошибка чтения данных с сервера...';
                 echo '<input class = "button1" type="submit" value="О б н о в и т ь"></p>';
                 exit();
              }
              else { OutData($res_view,$num_rows); }
            }
            else
            if ( $_POST['KOL_VO'] == 0 )
            {
              $oper_message = 'Список паролей пуст...';
              $query_view = 'select `id_sys`, `master`, `slovo1`, `slovo2`, `comment` from `mysystem` order by master, slovo1,slovo2';
              $res_view = $mysqli->query($query_view);
              if ( !$res_view ) 
              { 
                 echo '<p class="center_align">Ошибка чтения данных с сервера...';
                 echo '<input class = "button1" type="submit" value="О б н о в и т ь"></p>';
                 exit();
              }
              else { OutData($res_view,$num_rows); }
            }
            else
            {
               $idx_found = 0;
               for ( $j=1; $j<=$_POST['KOL_VO']; $j++)
               {
                 if ( isset($_POST['radio'.$j]) ) { $idx_found = $j; $id_sys = $_POST['radio'.$j]; }
               }
               if ( $idx_found == 0 )
               {
                 $oper_message = 'Не выбрана строка для изменения данных...';
                 $query_view = 'select `id_sys`, `master`, `slovo1`, `slovo2`, `comment` from `mysystem` order by master, slovo1,slovo2';
                 $res_view = $mysqli->query($query_view);
                 if ( !$res_view ) 
                 { 
                    echo '<p class="center_align">Ошибка чтения данных с сервера...';
                    echo '<input class = "button1" type="submit" value="О б н о в и т ь"></p>';
                    exit();
                 }
                 else { OutData($res_view,$num_rows); }
               }
               else
               {
                  $query_edit = 'Update `mysystem` SET master="'.$NewMas.'",slovo1="'.$NewLog.'",slovo2="'.$NewPas.'",'.
                                    'comment="'.$NewCom.'" where id_sys='.$id_sys;
                  $res_edit = $mysqli->query($query_edit);
                  if ( !$res_edit ) { $oper_message = 'Ошибка изменения данных...'; }
                  else { $oper_message = 'Данные успешно изменены...'; }
                  $query_view = 'select `id_sys`, `master`, `slovo1`, `slovo2`,`comment` from `mysystem` order by master, slovo1,slovo2';
                  $res_view = $mysqli->query($query_view);
                  if ( !$res_view ) 
                  { 
                     echo '<p class="center_align">Ошибка чтения данных с сервера...';
                     echo '<input class = "button1" type="submit" value="О б н о в и т ь"></p>';
                  }
                  else { OutData($res_view,$num_rows); }
               }
            }
          } // Edit

          echo '</div>';
          echo '</form>';
          if ( $oper_done ) { $message = $oper_message; }
          else { $message = ''; }
          echo '<form method="post" action="">
                <div class = "interface" style="margin-top: 4px;">
                <p class="center_align">'.$message.'
                <input autofocus class = "button1" type="submit" value="О б н о в и т ь"></p></div>
                </form>';
        }
     }
   } // есть логин и пароль...
   } // вход от головной формы
?>
