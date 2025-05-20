// ��������� ������ �� ������ XMLHttpRequest
var xmlHttp = createXmlHttpRequestObject();

// ������� ������ XMLHttpRequest
function createXmlHttpRequestObject()
{
    // ��� �������� ������ �� ������ XMLHttpRequest
    var xmlHttp;
    // ���� �������� ������� ��� ����������� Internet Explorer
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
   // ���� �������� ������� ��� ����������� Mozilla ��� ������� ��������
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
  // ������� ��������� ������ ��� ������� ��������� �� ������
  if (!xmlHttp)
      alert("������ �������� ������� XMLHttpRequest.");
  else
      return xmlHttp;
}
// ��������� ����������� ������ HTTP � ������� ������� XMLHttpRequest
function process()
{
// ������ �������� ������ ���� ������ xmlHttp �� �����
if (xmlHttp.readyState == 4 || xmlHttp.readyState == 0)
	{
	  // �������� ���, ��������� ������������� � �����
	  name = encodeURIComponent(document.getElementById("divMessage").value);
	  // ���������� � �������� quickstart.php �� �������
	  xmlHttp.open("GET", "http://127.0.0.1/AJAX_401/start_sql.php?name=" + name, true);
	  // ���������� �����, ������� ����� ������������ ������ �������
	  xmlHttp.onreadystatechange = handleServerResponse;
	  // ������� ����������� ������ �������
	  xmlHttp.send(null);
	}
else
// ���� ���������� ������, ��������� ������� ����� ���� �������
setTimeout('process()', 2000);
}

// ���������� ������������� �� �������� ��������� �� �������
function handleServerResponse()
{
   // ���������� ����� ������ ���� ���������� � �������� ���������
   if (xmlHttp.readyState == 4)
   {
       // �������� 200 ������� � ���, ��� ���������� ������ �������
      if ((xmlHttp.status == 200))
      {
          // ������� XML, ���������� �� �������
          //mlResponse = xmlHttp.responseXML;
       
	xmlResponse = xmlHttp.responseText;
       
         // �������� �������� ������� � ��������� XML
         xmlDocumentElement = xmlResponse;  //***.documentElement;
         // ������� ��������� ���������, ������� ��������� � ������
         // �������� �������� ��������� ����
         InfoMessage = xmlDocumentElement; //***.firstChild.data;
         // �������� ����� ��������� �� ������
         document.getElementById("divMessage").innerHTML =
            '<font face="Courier" size= "4">' + InfoMessage + '</font>';
         // ��������� ������������������ ��������
         setTimeout('process()', 2000);
        }
   // ��� ������� HTTP, �������� �� 200, ������� � ������� ������
   else
   {
       alert("��� ��������� � ������� �������� ��������: " +xmlHttp.statusText);
    }
  }
}
