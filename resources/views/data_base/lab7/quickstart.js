// запомнить ссылку на объект XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();

// создать объект XMLHttpRequest
function createXmlHttpRequestObject()
{
    // для хранения ссылки на объект XMLHttpRequest
    var xmlHttp;
    // если сценарий запущен под управлением Internet Explorer
   if(window.ActiveXObject)
   {
      try
     {
        xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
      }
     catch (e)
    {
       xmlHttp = false;
    }
   }
   // если сценарий запущен под управлением Mozilla или другого броузера
   else
   {
        try
        {
            xmlHttp = new XMLHttpRequest();
         }
        catch (e)
       {
            xmlHttp = false;
       }
   }
  // вернуть созданный объект или вывести сообщение об ошибке
  if (!xmlHttp)
      alert("Ошибка создания объекта XMLHttpRequest.");
  else
      return xmlHttp;
}
// выполнить асинхронный запрос HTTP с помощью объекта XMLHttpRequest
function process()
{
// работа возможна только если объект xmlHttp не занят
if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	{
	  // получить имя, введенное пользователем в форму
	  name = encodeURIComponent(document.getElementById("divMessage").value);
	  // обратиться к сценарию quickstart.php на сервере
	  xmlHttp.open("GET", "http://127.0.0.1/AJAX_401/start_sql.php?name=" + name, true);
	  // определить метод, который будет обрабатывать ответы сервера
	  xmlHttp.onreadystatechange = handleServerResponse;
	  // послать асинхронный запрос серверу
	  xmlHttp.send(null);
	}
else
// если соединение занято, повторить попытку через одну секунду
setTimeout('process()', 2000);
}

// вызывается автоматически по прибытии сообщения от сервера
function handleServerResponse()
{
   // продолжать можно только если транзакция с сервером завершена
   if (xmlHttp.readyState == 4)
   {
       // значение 200 говорит о том, что транзакция прошла успешно
      if ((xmlHttp.status == 200))
      {
          // извлечь XML, полученный от сервера
          //mlResponse = xmlHttp.responseXML;
       
	xmlResponse = xmlHttp.responseText;
       
         // получить корневой элемент в структуре XML
         xmlDocumentElement = xmlResponse;  //***.documentElement;
         // извлечь текстовое сообщение, которое находится в первом
         // дочернем элементе корневого узла
         InfoMessage = xmlDocumentElement; //***.firstChild.data;
         // обновить текст сообщения на экране
         document.getElementById("divMessage").innerHTML =
            '<font face="Courier" size= "4">' + InfoMessage + '</font>';
         // повторить последовательность действий
         setTimeout('process()', 2000);
        }
   // код статуса HTTP, отличный от 200, говорит о наличии ошибки
   else
   {
       alert("При обращении к серверу возникли проблемы: " +xmlHttp.statusText);
    }
  }
}
