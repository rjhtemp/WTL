import jakarta.servlet.http.*;  
import jakarta.servlet.*;  
import java.io.*; 
import java.sql.*;  

public class DemoServlet extends HttpServlet {

    public void doGet(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {
        res.setContentType("text/html");
        PrintWriter pw = res.getWriter();
        pw.println("<html><body>");

        pw.println("<h1>Pragati eBookShop</h1>");

        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection("jdbc:mysql://localhost:3306/pragati", "root", "");

            // Handle delete
            String delId = req.getParameter("deleteId");
            if (delId != null) {
                PreparedStatement ps = con.prepareStatement("DELETE FROM ebookshop WHERE book_id=?");
                ps.setInt(1, Integer.parseInt(delId));
                int rows = ps.executeUpdate();
                pw.println("<p style='color:red;'>Deleted " + rows + " record(s)</p>");
            }

            // Handle update form prefill
            String updateId = req.getParameter("updateId");
            if (updateId != null) {
                PreparedStatement ps = con.prepareStatement("SELECT * FROM ebookshop WHERE book_id=?");
                ps.setInt(1, Integer.parseInt(updateId));
                ResultSet rs = ps.executeQuery();

                if (rs.next()) {
                    pw.println("<h2>Update Book (ID: " + updateId + ")</h2>");
                    pw.println("<form method='post'>");
                    pw.println("<input type='hidden' name='book_id' value='" + updateId + "'/>");
                    pw.println("Name: <input type='text' name='book_name' value='" + rs.getString("book_name") + "'/><br>");
                    pw.println("Author: <input type='text' name='book_author' value='" + rs.getString("book_author") + "'/><br>");
                    pw.println("Price: <input type='text' name='book_price' value='" + rs.getDouble("book_price") + "'/><br>");
                    pw.println("Quantity: <input type='text' name='quantity' value='" + rs.getInt("quantity") + "'/><br>");
                    pw.println("<input type='submit' name='action' value='Update'/>");
                    pw.println("</form><hr>");
                }
            }

            // Show book list
            Statement stmt = con.createStatement();
            ResultSet rs = stmt.executeQuery("SELECT * FROM ebookshop");

            pw.println("<h2>Book List</h2>");
            pw.println("<table border='1'>");
            pw.println("<tr><th>ID</th><th>Name</th><th>Author</th><th>Price</th><th>Quantity</th><th>Actions</th></tr>");

            while (rs.next()) {
                int id = rs.getInt("book_id");
                pw.println("<tr><td>" + id + "</td><td>" + rs.getString("book_name") + "</td><td>" + rs.getString("book_author") + "</td><td>" + rs.getDouble("book_price") + "</td><td>" + rs.getInt("quantity") + "</td>");
                pw.println("<td><a href='it?updateId=" + id + "'></a> | <a href='it?deleteId=" + id + "' onclick='return confirm(\"Are you sure?\")'>Delete</a></td></tr>");
            }
            pw.println("</table><hr>");

            // Insert form
            pw.println("<h2>Add New Book</h2>");
            pw.println("<form method='post'>");
            pw.println("Name: <input type='text' name='book_name'/><br>");
            pw.println("Author: <input type='text' name='book_author'/><br>");
            pw.println("Price: <input type='text' name='book_price'/><br>");
            pw.println("Quantity: <input type='text' name='quantity'/><br>");
            pw.println("<input type='submit' name='action' value='Insert'/>");
            pw.println("</form>");

            con.close();

        } catch (Exception e) {
            pw.println("<p style='color:red;'>Error: " + e.getMessage() + "</p>");
        }

        pw.println("</body></html>");
        pw.close();
    }

    public void doPost(HttpServletRequest req, HttpServletResponse res) throws ServletException, IOException {
        res.setContentType("text/html");
        PrintWriter pw = res.getWriter();

        String action = req.getParameter("action");
        String name = req.getParameter("book_name");
        String author = req.getParameter("book_author");
        String price = req.getParameter("book_price");
        String qty = req.getParameter("quantity");

        try {
            Class.forName("com.mysql.jdbc.Driver");
            Connection con = DriverManager.getConnection("jdbc:mysql://localhost:3306/pragati", "root", "");

            if ("Insert".equals(action)) {
                PreparedStatement ps = con.prepareStatement("INSERT INTO ebookshop (book_name, book_author, book_price, quantity) VALUES (?, ?, ?, ?)");
                ps.setString(1, name);
                ps.setString(2, author);
                ps.setDouble(3, Double.parseDouble(price));
                ps.setInt(4, Integer.parseInt(qty));
                int rows = ps.executeUpdate();
                pw.println("<p style='color:green;'>Inserted " + rows + " record(s)</p>");
            } else if ("Update".equals(action)) {
                int id = Integer.parseInt(req.getParameter("book_id"));
                PreparedStatement ps = con.prepareStatement("UPDATE ebookshop SET book_name=?, book_author=?, book_price=?, quantity=? WHERE book_id=?");
                ps.setString(1, name);
                ps.setString(2, author);
                ps.setDouble(3, Double.parseDouble(price));
                ps.setInt(4, Integer.parseInt(qty));
                ps.setInt(5, id);
                int rows = ps.executeUpdate();
                pw.println("<p style='color:blue;'>Updated " + rows + " record(s)</p>");
            }

            con.close();

        }
        catch (Exception e) 
        {
            pw.println("<p style='color:red;'>Error: " + e.getMessage() + "</p>");
        }

        res.sendRedirect("it");
    }
}
