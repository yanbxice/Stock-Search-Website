import java.io.*;
import java.util.*;
import javax.servlet.*;
import javax.servlet.http.*;
import java.net.*;
import org.jdom.Document; 
import org.jdom.Element; 
import org.jdom.JDOMException; 
import org.jdom.input.SAXBuilder; 
import org.jdom.output.XMLOutputter;
import org.json.*;

public class HelloWorldExample extends HttpServlet {
	public void doGet(HttpServletRequest request, HttpServletResponse response) throws IOException, ServletException{
		String company = (String)request.getParameter("company");
		response.setContentType("text/html");
		PrintWriter out = response.getWriter();
		//out.println("company = " + company + "\n");
		String urlString = "http://default-environment-f3vmk22jmw.elasticbeanstalk.com/?";
		urlString = urlString + "company=" + company;
		URL url = new URL(urlString); 
		URLConnection urlConnection = url.openConnection(); 
		urlConnection.setAllowUserInteraction(false); 
		InputStream urlStream = url.openStream();
		
		SAXBuilder builder=new SAXBuilder(false); 
		try{
			//out.println("jinlai");
			Document doc=builder.build(urlStream);
			Element result=doc.getRootElement();
			String name = result.getChildTextTrim("Name");
			//out.println(name);
			if (name != ""){
			//out.println("name");
			String symbol = result.getChildTextTrim("Symbol");
			Element quote = result.getChild("quote");
			List paramList = quote.getChildren();
			Element news = result.getChild("News");
			List items = news.getChildren("Item");
			String image = result.getChildTextTrim("StockChartImageURL");
			
			JSONObject json = new JSONObject();
			JSONObject jsonquote = new JSONObject();
			JSONObject jsonitems = new JSONObject();
			JSONObject jsonnews = new JSONObject();
			JSONObject jsonimage = new JSONObject();
			JSONArray arr = new JSONArray();
			JSONObject finance = new JSONObject();
			finance.put("Name", name);
			finance.put("Symbol", symbol);
            Iterator iter = paramList.iterator();
            while ( iter.hasNext() ){
                Element param = (Element) iter.next();
                jsonquote.put(param.getName(), quote.getChildTextTrim(param.getName()));
            }
			finance.put("quote", jsonquote);
			//json.put("result", finance);
			
			
				Iterator iter1 = items.iterator();
				int i = 0;
				while(iter1.hasNext()){
					Element item = (Element) iter1.next();
					JSONObject temp = new JSONObject();
					temp.put("Title", item.getChildTextTrim("Title"));
					temp.put("Link", item.getChildTextTrim("Link"));
					arr.put(temp);
					//out.println(i++);
				}
			
			jsonnews.put("Item", arr);
			finance.put("News", jsonnews);
			finance.put("StockChartImageURL", image);
			json.put("result", finance);
			out.println(json.toString());
			}
			else{
				JSONObject json = new JSONObject();
				JSONObject finance = new JSONObject();
				finance.put("Name", "Stock Information Not Found!");
				json.put("result", finance);
				out.println(json.toString());
			}

		} catch (JDOMException e) {
			e.printStackTrace();
		} catch (IOException e) {
			e.printStackTrace();
		} 
		
		out.flush();  
        out.close();
		
		
	}
}